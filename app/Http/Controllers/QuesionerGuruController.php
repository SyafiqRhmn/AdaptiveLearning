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

class QuesionerGuruController extends Controller
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
    $ahpResults = $this->calculateAHPResults($resultMatrix, $totalCol);

    if ($kriteriaExists) {
        return view('dashboard.guru.qu-guru.matrix', [
            'title' => 'Quesioner Matrix',
            'kriteria' => $kriteria,
            'poinCount' => $poinCount,
            'matrix' => $matrix,
            'totalCol' => $totalCol,
            'resultMatrix' => $resultMatrix,
            'total' => $ahpResults['total'],
            'ev' => $ahpResults['ev'],
            'emax' => $ahpResults['emax'],
            'ci' => $ahpResults['ci'],
            'cr' => $ahpResults['cr'],
        ]);
    } else {
        // Jika tidak ada, kembali ke halaman pemilihan kriteria
        return view('dashboard.guru.qu-guru.index', [
            'title' => 'Quesioner Guru',
            'kriteria' => $kriteria,
            'kuisioners' => $kuisioners,
            'quesioner' => $quesioners,
            'poinCount' => $poinCount,
            'counter' => $counter
            ]);
    }
    
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

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('qu-guru.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show($kriteria)
    {
        // Ambil data perbandingan untuk kriteria yang dipilih dari tabel 'kriteria_values'
        $comparisons = KriteriaValue::All();
        // Inisialisasi array matriks
        $poinCount = QuesionerGuru::where('kriteria', $kriteria)->count(); // Misalnya ada 5 poin (V1, V2, V3, V4, V5)
        // Inisialisasi matriks
        $result = $this->initializeMatrix($poinCount, $comparisons);
        $matrix = $result['matrix'];
        $totalCol = $result['totalCol'];
        // Perkalian matriks
        $resultMatrix = $this->multiplyMatrices($matrix);
        // Hitung hasil AHP menggunakan matriks yang sudah dibentuk
        $ahpResults = $this->calculateAHPResults($resultMatrix, $totalCol);

        // Kirim matriks ke view untuk ditampilkan
        return view('dashboard.guru.qu-guru.matrix', [
            'title' => 'Quesioner Matrix',
            'kriteria' => $kriteria,
            'poinCount' => $poinCount,
            'matrix' => $matrix,
            'totalCol' => $totalCol,
            'resultMatrix' => $resultMatrix,
            'total' => $ahpResults['total'],
            'ev' => $ahpResults['ev'],
            'emax' => $ahpResults['emax'],
            'ci' => $ahpResults['ci'],
            'cr' => $ahpResults['cr'],
        ]);
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

    public function calculateAHPResults($matrix, $totalCol)
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
        // Simpan nilai EV ke tabel baru
        foreach ($ev as $i => $evValue) {
            // Tentukan kriteria berdasarkan indeks
            $kriteriaPrefix = '';
            if ($i <= 5) { // Misalnya, 1 dan 2 adalah kriteria V
                $kriteriaPrefix = 'V';
            } elseif ($i <= 10) { // Misalnya, 3 dan 4 adalah kriteria A
                $kriteriaPrefix = 'A';
            } else { // Misalnya, 5 adalah kriteria K
                $kriteriaPrefix = 'K';
            }
            Ev::updateOrCreate(
                ['kuisioners_id' => $i, 'kriteria' => $kriteriaPrefix.$i], // Sesuaikan kriteria
                ['ev_value' => $evValue] // Simpan nilai EV
            );
        }

        // Hitung Emax dari total kolom dan EV
        $emax = 0;
        foreach ($totalCol as $i => $columnTotal) {
            $emax += $columnTotal * $ev[$i]; // Kalikan total kolom dengan EV
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
