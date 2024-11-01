<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Ev;
use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\Kuisioner;
use App\Models\KriteriaValue;
use App\Models\QuesionerGuru;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateQuesionerRequest;

class PakarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $quesioners = QuesionerGuru::all();
    // Ambil kriteria dari query string atau form
    $kriteria = $request->input('kriteria');
    // Periksa apakah kriteria ada di tabel kriteria_value
    $kriteriaExists = KriteriaValue::where('kriteria', $kriteria)->exists();
     // Ambil semua kuisioner berdasarkan kriteria
    $kuisioners = QuesionerGuru::where('kriteria', $kriteria)->get();
    // Menghitung jumlah pertanyaan berdasarkan kriteria
    $poinCount = QuesionerGuru::where('kriteria', $kriteria)->count();; 
    $counter = 1;
    // Ambil data perbandingan untuk kriteria yang dipilih dari tabel 'kriteria_values'
    $comparisons = KriteriaValue::where('kriteria', $kriteria)->get();
      
    // Inisialisasi matriks
    $result = $this->initializeMatrix($poinCount, $comparisons);
    $matrix = $result['matrix'];
    $totalCol = $result['totalCol'];
    // Perkalian matriks
    $resultMatrix = $this->multiplyMatrices($matrix);
    // Hitung hasil AHP menggunakan matriks yang sudah dibentuk
    $ahpResults = $this->calculateAHPResults($resultMatrix, $totalCol, $kriteria);


    return view('dashboard.pakar.qu-dosen.index', [
        'title' => 'Quesioner Dosen',
        'kriteria' => $kriteria,
        'kuisioners' => $kuisioners,
        'quesioner' => $quesioners,
        'poinCount' => $poinCount,
        'counter' => $counter,
        'kriteria' => $kriteria,
        'poinCount' => $poinCount,
        'matrix' => $matrix,
        'kriteriaExists' =>  $kriteriaExists,
        'totalCol' => $totalCol,
        'resultMatrix' => $resultMatrix,
        'total' => $ahpResults['total'],
        'ev' => $ahpResults['ev'],
        'emax' => $ahpResults['emax'],
        'ci' => $ahpResults['ci'],
        'cr' => $ahpResults['cr'],
        ]);
    
    
    }   


    public function storeMatrix(Request $request)
    {

        // Ambil semua input yang diajukan
        $data = $request->except('_token'); // Mengabaikan token CSRF
        // Loop untuk menyimpan nilai ke dalam model
        foreach ($data as $key => $value) {
            // Gunakan regex untuk menangkap bagian 'kriteria', 'i', dan 'j' dari nama input
            if (preg_match('/comparison_(\w)_(\d+)_(\d+)/', $key, $matches)) {
                $kriteria = $matches[1]; // 'V', 'A', atau 'K'
                $i = $matches[2]; // Indeks pertama
                $j = $matches[3]; // Indeks kedua

                 // Validasi tambahan (misalnya, pastikan nilai radio button dalam rentang yang benar)
                if (in_array($value, range(1, 9))) {
                    // Buat instance baru dari model KriteriaValue
                    $kriteriaValue = new KriteriaValue();
                    $kriteriaValue->kriteria = $kriteria;
                    $kriteriaValue->point1 = $i;
                    $kriteriaValue->point2 = $j;
                    $kriteriaValue->value = $value; // Nilai dari radio button (1-9)
                    $kriteriaValue->save(); // Simpan data ke database
                }
            }
        }
        $poinCount = QuesionerGuru::where('kriteria', $kriteria)->count();; 
        $counter = 1;
        // Ambil data perbandingan untuk kriteria yang dipilih dari tabel 'kriteria_values'
        $comparisons = KriteriaValue::where('kriteria', $kriteria)->get();
        
        // Inisialisasi matriks
        $result = $this->initializeMatrix($poinCount, $comparisons);
        $matrix = $result['matrix'];
        $totalCol = $result['totalCol'];
        // Perkalian matriks
        $resultMatrix = $this->multiplyMatrices($matrix);
        // Hitung hasil AHP menggunakan matriks yang sudah dibentuk
        $ahpResults = $this->calculateAHPResults($resultMatrix, $totalCol, $kriteria);
        

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('qu-dosen.index')->with('success', 'Data berhasil disimpan.');
    }

    
    public function initializeMatrix($poinCount, $comparisons)
    {
        $matrix = [];
        $totalCol = array_fill(1, $poinCount, 0); // Inisialisasi total kolom

        // Inisialisasi matriks identitas
        for ($i = 1; $i <= $poinCount; $i++) {
            for ($j = 1; $j <= $poinCount; $j++) {
                if ($i == $j) {
                    $matrix[$i][$j] = 1; // Perbandingan terhadap dirinya sendiri = 1
                } else {
                    $matrix[$i][$j] = null; // Inisialisasi sebagai null
                }
            }
        }

        // Isi matriks dengan nilai perbandingan
        foreach ($comparisons as $comparison) {
            $point1 = $comparison->point1;
            $point2 = $comparison->point2;
            $value = $comparison->value;

            $matrix[$point1][$point2] = $value;
            $matrix[$point2][$point1] = 1 / $value; // Nilai inverse
        }

        // Hitung total per kolom
        for ($j = 1; $j <= $poinCount; $j++) {
            for ($i = 1; $i <= $poinCount; $i++) {
                $totalCol[$j] += $matrix[$i][$j] ?? 0; // Penjumlahan setiap elemen kolom
            }
        }

        return [
            'matrix' => $matrix,
            'totalCol' => $totalCol,
        ];
    }

    private function multiplyMatrices($matrix)
    {
        $size = count($matrix);
        $result = [];

        // Inisialisasi matriks hasil
        for ($i = 1; $i <= $size; $i++) {
            for ($j = 1; $j <= $size; $j++) {
                $result[$i][$j] = 0; // Inisialisasi dengan 0
                for ($k = 1; $k <= $size; $k++) {
                    $valueA = $matrix[$i][$k] ?? 0;
                    $valueB = $matrix[$k][$j] ?? 0;
                    $result[$i][$j] += $valueA * $valueB; // Penjumlahan hasil perkalian
                }
            }
        }

        return $result; 
    }

    public function calculateAHPResults($matrix, $totalCol, $kriteria)
{
    // Hitung total dan EV
    
    $total = [];
    $ev = [];
    $poinCount = count($matrix);
    
    // Hitung total setiap baris
    for ($i = 1; $i <= $poinCount; $i++) {
        $rowTotal = 0; // Inisialisasi total untuk baris ke-i
        for ($j = 1; $j <= $poinCount; $j++) {
            $rowTotal += $matrix[$i][$j] ?? 0; // Penjumlahan setiap elemen baris
        }
        $total[$i] = $rowTotal; // Simpan total baris
    }

    // Hitung EV dan Total keseluruhan
    $totalSum = array_sum($total); // Hitung jumlah total seluruh baris
    for ($i = 1; $i <= $poinCount; $i++) {
        $ev[$i] = $total[$i] / ($totalSum > 0 ? $totalSum : 1); // Normalisasi EV
    }
    
    foreach ($ev as $i => $evValue) {
        // Tentukan kriteria berdasarkan parameter yang diberikan
        $kriteriaPrefix = '';
        $kuisioners_id = 0; // Inisialisasi kuisioners_id

        if ($kriteria === 'V') {
            $kriteriaPrefix = 'V';
            $kuisioners_id = $i; // 1-5
        } elseif ($kriteria === 'A') {
            $kriteriaPrefix = 'A';
            $kuisioners_id = $i + 5; // 6-10
        } elseif ($kriteria === 'K') {
            $kriteriaPrefix = 'K';
            $kuisioners_id = $i + 10; // 11-15
        }

        Ev::updateOrCreate(
            ['kuisioners_id' => $kuisioners_id, 'kriteria' => $kriteriaPrefix.$i], // Sesuaikan kriteria
            ['ev_value' => $evValue] // Simpan nilai EV
        );
    }

    // Hitung Emax dari total kolom dan EV
    $emax = 0;
    foreach ($totalCol as $i => $columnTotal) {
        $emax += $columnTotal * ($ev[$i] ?? 0); // Kalikan total kolom dengan EV
    }

    // Hitung CI dan CR
    $n = $poinCount; // Jumlah kriteria
    $ci = ($emax - $n) / ($n - 1);
    $ri = $this->getRIValue($n); // Ambil nilai RI berdasarkan jumlah kriteria
    $cr = $ri > 0 ? $ci / $ri : 0; // Pengecekan untuk CR

    return [
        'total' => $total,
        'ev' => $ev,
        'emax' => $emax,
        'ci' => $ci,
        'cr' => $cr,
    ];
}


    public function destroy(Request $request)
    {
    // Ambil kriteria dari input form
    $kriteria = $request->input('kriteria');

    // Hapus data dari model 'Ev' berdasarkan kriteria
    Ev::where('kriteria', $kriteria)->delete();

    // Hapus data dari model 'KriteriaValue' berdasarkan kriteria
    KriteriaValue::where('kriteria', $kriteria)->delete();
        return redirect()->route('qu-dosen.index')->with('success', 'Quesioner berhasil dihapus');
    }

    private function getRIValue($n)
    {
        // Nilai RI berdasarkan jumlah kriteria
        $riValues = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        return $riValues[$n] ?? 0; // Kembalikan 0 jika n tidak ada dalam daftar
    }

}
