<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Answer;
use App\Models\AnswerKuisioner;
use App\Models\Ev;
use App\Models\PreTest;
use App\Models\Subject;
use App\Models\PostTest;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\KelasSiswa;
use App\Models\Kuisioner;
use App\Models\CourseTest;
use App\Models\NilaiPelajar;
use Illuminate\Http\Request;
use App\Libraries\PythonWrapper;
use App\Models\HasilTestPelajar;
use App\Models\JalurPembelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelajarRegulerController extends Controller
{
    public function index()
    {
        $kuisioner = kuisioner::All();
        $no = 1;
        //mengecek apakah tabel jawaban kuisioner ada berdasarkan id
        $kriteriaExists = User::find(Auth::id())->answerKuisioners()->exists();
        //mengambil data jawaban berdasarkan id
        $jawaban = User::find(Auth::id())->answerKuisioners;
        
        $resultcalculate = $this->calculateMatrix();
        // dd($resultcalculate['matrix_ideal']);
        // Perkalian matriks
        $resultKuisioner = $this->showResults();
        return view('dashboard.nonpersonalisasi.kuisioner', [
            'title' => 'Dashboard Kuisioner',
            'kuisioner' => $kuisioner,
            'jawaban' => $jawaban,
            'no' => $no,
            'matrix' => $resultKuisioner['matrix'],
            'normalized_matrix' => $resultKuisioner['normalized_matrix'],
            'kriteriaExists' => $kriteriaExists,
            'bobot' => $resultcalculate['matrix'],
            'matrix_ideal' => $resultcalculate['matrix_ideal'],
            'ideal_positif' => $resultcalculate['ideal_positif'],
            'ideal_negatif' => $resultcalculate['ideal_negatif'],
            'preferensi' => $resultcalculate['preferensi'],
            'ranking' => $resultcalculate['ranking'],
            // 'classrooms' => Classroom::paginate(10),
            // 'my_classes' => $my_classes,
        ]);
    }

    public function saveKuisioner(Request $request)
    {

        
        $validatedData = $request->validate([
            'answers' => 'required|array', // Pastikan ada array untuk jawaban
            'answers.*' => 'required|integer|between:1,5',
        ]);
        // Menyimpan jawaban ke database
        foreach ($validatedData['answers'] as $questionId => $value) {
            AnswerKuisioner::create([
                'kuisioners_id' => $questionId, // ID pertanyaan dari kuisioner
                'value' => $value, // Nilai jawaban
                'user_id' => Auth::id(),
            ]);
        }

        // // Save the user to the database
        // $quesioner->save();

        // Redirect the user to the home page with a success message
        return redirect()->back()->with('success', 'Answer Quisioner has been added!');
    }

    // MENAMPILKAN NILAI KUISIONER
    public function showResults()
    {
        // Mengambil semua data kuisioner dari database
        $answers = User::find(Auth::id())->answerKuisioners;
         // Inisialisasi matriks
        $matrix = [
            'V' => [],
            'A' => [],
            'K' => [],
        ];

        foreach ($answers as $answer) {

            // Menyusun nilai berdasarkan kuisioners_id
            if ($answer->kuisioners_id >= 1 && $answer->kuisioners_id <= 5) {
                // Menyimpan nilai pada kriteria V
                $matrix['V'][$answer->kuisioners_id] = $answer->value;
            } elseif ($answer->kuisioners_id >= 6 && $answer->kuisioners_id <= 10) {
                // Menyimpan nilai pada kriteria A
                $matrix['A'][$answer->kuisioners_id - 5] = $answer->value; // Mengurangi 5 untuk mendapatkan index 1-5
            } elseif ($answer->kuisioners_id >= 11 && $answer->kuisioners_id <= 15) {
                // Menyimpan nilai pada kriteria K
                $matrix['K'][$answer->kuisioners_id - 10] = $answer->value; // Mengurangi 10 untuk mendapatkan index 1-5
            }
        }
        

        // Menghitung normalisasi
        $normalized_matrix = [];
        foreach ($matrix['V'] as $index => $value) {
            // Ambil nilai V, A, dan K
            $vValue = $matrix['V'][$index] ?? 0; // Nilai V
            $aValue = $matrix['A'][$index] ?? 0; // Nilai A
            $kValue = $matrix['K'][$index] ?? 0; // Nilai K

            // Normalisasi
            $denominator = sqrt(pow($vValue, 2) + pow($aValue, 2) + pow($kValue, 2));
            $normalized_v = ($denominator != 0) ? $vValue / $denominator : 0;
            $normalized_a = ($denominator != 0) ? $aValue / $denominator : 0;
            $normalized_k = ($denominator != 0) ? $kValue / $denominator : 0;

            // Simpan hasil normalisasi
            $normalized_matrix['V'][] = $normalized_v;
            $normalized_matrix['A'][] = $normalized_a;
            $normalized_matrix['K'][] = $normalized_k;
        }

        return ([
            'matrix' => $matrix,
            'normalized_matrix' => $normalized_matrix,
        ]);
    }

    public function calculateMatrix()
    {
        $resultKuisioner = $this->showResults();
        $normalized_matrix = $resultKuisioner['normalized_matrix'];
        // Ambil semua nilai ev dan answers berdasarkan kuisioners_id
        $answers = User::find(Auth::id())->answerKuisioners;
        // dd($normalized_matrix);
        $evValues = Ev::with('answers')->get();
    
        // Inisialisasi matriks
        $matrix = [
            'V' => [],
            'A' => [],
            'K' => [],
        ];
    
        // Buat array untuk menyimpan ev_value berdasarkan kuisioners_id
        $evValueMap = [];
        foreach ($evValues as $ev) {
            $evValueMap[$ev->kuisioners_id] = $ev->ev_value; // Simpan ev_value berdasarkan kuisioners_id
        }

        // Iterasi untuk mengisi matriks dan mengalikan nilai
        foreach ($answers as $answer) {
            $kuisionersId = $answer->kuisioners_id;

            if ($kuisionersId >= 1 && $kuisionersId <= 5) {
                // Menyimpan nilai pada kriteria V
                $index = $kuisionersId - 1; // Sesuaikan dengan indeks 0
                $matrix['V'][$index] = $normalized_matrix['V'][$index] * ($evValueMap[$kuisionersId] ?? 0); // Kalikan dengan ev_value
            } elseif ($kuisionersId >= 6 && $kuisionersId <= 10) {
                // Menyimpan nilai pada kriteria A
                $index = $kuisionersId - 6; // Sesuaikan dengan indeks 0
                $matrix['A'][$index] = $normalized_matrix['A'][$index] * ($evValueMap[$kuisionersId] ?? 0); // Kalikan dengan ev_value
            } elseif ($kuisionersId >= 11 && $kuisionersId <= 15) {
                // Menyimpan nilai pada kriteria K
                $index = $kuisionersId - 11; // Sesuaikan dengan indeks 0
                $matrix['K'][$index] = $normalized_matrix['K'][$index] * ($evValueMap[$kuisionersId] ?? 0); // Kalikan dengan ev_value
            }
        }
        // Inisialisasi array untuk menyimpan hasil akhir
        $matrix_ideal = [];
        for ($i = 0; $i < 5; $i++) { // Misalkan Anda memiliki 5 indeks
        $matrix_ideal[$i] = [
            'max' => max(
                $matrix['V'][$i] ?? 0,
                $matrix['A'][$i] ?? 0,
                $matrix['K'][$i] ?? 0
            ),
            'min' => min(
                $matrix['V'][$i] ?? PHP_INT_MAX, // Gunakan PHP_INT_MAX untuk memastikan minimum benar
                $matrix['A'][$i] ?? PHP_INT_MAX,
                $matrix['K'][$i] ?? PHP_INT_MAX
            ),
        
        ];
        }
        // Inisialisasi jarak ideal
        $poinCount = count($matrix['V']); 
        $ideal_positif = [];
        $ideal_negatif = [];
        // Hitung jarak ideal
        foreach (['V', 'A', 'K'] as $kriteria) {
            $ideal_positif[$kriteria] = 0; // Inisialisasi untuk kriteria ini
            $ideal_negatif[$kriteria] = 0; // Inisialisasi untuk kriteria ini
            
            for ($i = 0; $i < $poinCount; $i++) {
                // Hitung D+ (jarak ke ideal positif)
                $ideal_positif[$kriteria] += pow(($matrix[$kriteria][$i] ?? 0) - $matrix_ideal[$i]['max'], 2);
                // Hitung D- (jarak ke ideal negatif)
                $ideal_negatif[$kriteria] += pow(($matrix[$kriteria][$i] ?? 0) - $matrix_ideal[$i]['min'], 2);
            }
        }

        // Mengambil akar kuadrat dari total D+ dan D-
        foreach (['V', 'A', 'K'] as $kriteria) {
            $ideal_positif[$kriteria] = sqrt($ideal_positif[$kriteria]);
            $ideal_negatif[$kriteria] = sqrt($ideal_negatif[$kriteria]);
        }

        // Inisialisasi array untuk menyimpan nilai preferensi
        $preferensi = [];

        // Hitung preferensi untuk setiap kriteria
        foreach (['V', 'A', 'K'] as $kriteria) {
            // Pastikan ideal_positif dan ideal_negatif adalah angka bukan array
            $preferensi[$kriteria] = $ideal_negatif[$kriteria] / 
                (($ideal_positif[$kriteria] + $ideal_negatif[$kriteria]) > 0 
                    ? ($ideal_positif[$kriteria] + $ideal_negatif[$kriteria]) 
                    : 1); // Hindari pembagian dengan nol
        }

        // Mengurutkan preferensi dan menentukan ranking
        arsort($preferensi); // Mengurutkan preferensi secara menurun
        // Mengonversi ranking ke dalam format 1, 2, 3
        $ranking = [];
        $counter = 1;
        foreach (array_keys($preferensi) as $key) {
            $ranking[$key] = $counter++; // Menetapkan nilai ranking 1, 2, 3
        }

        // Debugging untuk memeriksa hasil
        return ([
            'matrix' => $matrix,
            'matrix_ideal' => $matrix_ideal,
            'ideal_positif' => $ideal_positif,
            'ideal_negatif' => $ideal_negatif,
            'preferensi' => $preferensi,
            'ranking'   => $ranking
        ]);
    }
        
    public function setting_akun()
    {
        $userId = Auth::id();
        $myprofile = User::find($userId);
        return view('dashboard.nonpersonalisasi.setting.setting_akun',[
            'title'=> 'Setting Akun',
            'myprofile' => $myprofile
        ]);
    }
    public function my_class ()
    {
        $userId = Auth::id();
        $my_class = User::find($userId)->kelasSiswa;
        return view('dashboard.nonpersonalisasi.index', [
            'title' => 'Dashboard pelajar reguler',
            'classrooms' => $my_class,
            'myClassrooms' => null
        ]);
    }
    public function edit_akun($userID)
{
    // Mendapatkan ID pengguna yang sedang login
    $userID = Auth::id();
    // Mendapatkan profil pengguna yang ingin diedit
    $myprofile = User::find($userID);
    
    // Menyimpan perubahan data pengguna jika ada input dari form
    if(request()->isMethod('put')) {
        $myprofile->name = request()->input('name');
        $myprofile->email = request()->input('email');
        $myprofile->tipe = request()->input('tipe');
        $myprofile->save();
        return redirect()->route('reguler.settingAkun')->with('success', 'Profil berhasil diperbarui');
    }

    return view('dashboard.nonpersonalisasi.setting.setting_akun', [
        'title' => 'Setting Akun',
        'myprofile' => $myprofile
    ]);
}
    
    public function allClassReguler ()
    {
        $my_class = User::find(Auth::id())->kelasSiswa;
        $my_classes = $my_class->pluck('classroom_id')->toArray();
        return view('dashboard.nonpersonalisasi.all_class', [
            'title' => 'Dashboard pelajar reguler',
            'classrooms' => Classroom::paginate(10),
            'my_classes' => $my_classes,
        ]);
    }
    
    public function ikutiKelasReguler ($classroomID)
    {
        $KelasSiswa = new KelasSiswa;
        $KelasSiswa->user_id = Auth::id();
        $KelasSiswa->classroom_id = $classroomID;
        $KelasSiswa->save();

        $userId = Auth::id();
        $my_class = User::find($userId)->kelasSiswa;
        return redirect()->route('reguler.my-class')->with([
            'title' => 'Dashboard pelajar reguler',
            'classrooms' => $my_class,
            'myClassrooms' => null
        ]);
    }
    
    public function my_classReguler ($classroomID)
    {
        $class = Classroom::where('id', $classroomID)->get()[0];
        $user = auth()->user();
        // $startSubject = $user->id_modul_awal_reguler;
        // $subjects = JalurPembelajaran::where('user_id', Auth::id())  
        // ->whereHas('subject', function ($query) use ($classroomID) {
        // $query->where('classroom_id', $classroomID); })->orderBy('id')->get();
        // if ($subjects->isEmpty()) {
        $subjects = Subject::where('classroom_id', $classroomID)->get();
    
        // Ambil semua modul yang terkait dengan kelas dan pengguna
        $modulData = JalurPembelajaran::where('user_id', Auth::id())
                        ->where('user_id', $user->id)
                        ->whereHas('subject', function ($query) use ($classroomID) { 
                            $query->where('classroom_id', $classroomID);})
                        ->get();
        // Jika tidak ada modul terkait classroom ini, otomatis tidak bisa post-test
        $canTakePostTest = $modulData->isNotEmpty(); 

        if ($canTakePostTest) {
            // Jika modul ditemukan, periksa apakah sudah menonton minimal 20 menit
            foreach ($modulData as $modul) {
                $takentime = strtotime($modul->endtime_video) - strtotime($modul->starttime_video);

                // Jika ada modul yang belum ditonton selama 20 menit, tidak bisa ambil post-test
                if ($takentime < 1200) {
                    $canTakePostTest = false; // Set menjadi false jika durasi kurang dari 20 menit
                    break;
                }
            }
        }
        // }
        
        // $hasilTestPelajar = HasilTestPelajar::where('user_id', Auth::user()->id)->where('testable_type', 'pre-test')->get();
        // $test = null;
        // if ($hasilTestPelajar !== null) {
        //     foreach ($hasilTestPelajar as $htp) {
        //         echo $htp->classroom_id;
        //         $pt = PreTest::where('id', $htp->testable_id)->first();
        //         if ($pt !== null && $pt->classroom_id == $classroomID) {
        //             $test = $pt;
        //         }
        //     }
        // }
        // $subjects = JalurPembelajaran::where('user_id', Auth::id())
        // ->whereHas('subject', function ($query) use ($classroomID) {
        //     $query->where('classroom_id', $classroomID);
        // })->orderBy('id')->get();

        // if ($subjects->isEmpty()) {
        //     $subjects = Subject::where('classroom_id', $classroomID)->get();
        // }
        // Cek apakah pre-test sudah dikerjakan untuk user di class ini
        $pretest = HasilTestPelajar::where('user_id', Auth::id())
        ->where('testable_type', 'pre-test')
        ->where('testable_id', $classroomID)
        ->first();
        return view('dashboard.nonpersonalisasi.class', [
            'title' => $class->name,
            'classroomID' => $classroomID,
            // 'test' => $test,
            'canTakePostTest' => $canTakePostTest,
            'class' => $class,
            'subjects' => $subjects,
            'pretest' => $pretest, // Kirim hasil pretest
            // 'startSubject'=> $startSubject,
        ]);
    }

    public function checkPostTest()
{
    $user = auth()->user();
    
    // Ambil semua modul yang telah diakses siswa
    $modulData = DB::table('jalur_pembelajarans')
                    ->where('user_id', $user->id)
                    ->get();

    foreach ($modulData as $modul) {
        // Hitung total waktu yang diambil untuk video
        $takentime = strtotime($modul->endtime_video) - strtotime($modul->starttime_video);
        
        // Jika waktu kurang dari 20 menit (1200 detik), batalkan akses ke post-test
        if ($takentime < 1200) {
            return redirect()->back()->withErrors(['message' => 'Anda harus menonton modul selama minimal 20 menit sebelum mengerjakan post-test.']);
        }
    }
    
    // Jika semua modul telah dilihat selama minimal 20 menit
    return redirect()->route('reguler.test.do', ['test' => 'post-test', 'classroomID' => $classroomID]);
}

    public function Regulertest_do($jenis_test, $classroomID)
{
    $class = Classroom::where('id', $classroomID)->first();
    $testID = PreTest::where('id', $classroomID)->first()->id;
    $user = auth()->user();

    // Mengambil semua pertanyaan untuk kelas dan jenis test yang sesuai
    $questions = Question::where('testable_type', $jenis_test)
        ->where('testable_id', $testID)
        ->get();

    $result = array();
    $result_choose = array();
    
    foreach ($questions as $key) {
        $result[$key->id] = null;
    }

    return view('dashboard.nonpersonalisasi.test.test', [
        'title' => $jenis_test . ' ' . $class->name,
        'jenis_test' => $jenis_test,
        'testID' => $testID,
        'questions' => $questions,
        'result' => $result,
        'result_choose' => $result_choose,
        'question' => $questions->isNotEmpty() ? $questions[0] : null, // Cek jika ada pertanyaan
    ]);
}

    public function Regulertest (Request $request, $jenis_test, $question_target)
    {
        $result = json_decode($request->input('result'), true);
        $result_choose = json_decode($request->input('result_choose'), true);
        if ($request->answer) {
            $result[$request->question_id] = Answer::find($request->answer)->benar_salah;
            $result_choose[$request->question_id] = $request->answer;
        }
        
        $question = Question::where('id', $question_target)->first();
        $user = auth()->user();
        // $startSubjectID = $user->id_modul_awal_reguler;
        // $endSubjectID = $user->id_modul_tujuan_reguler;
        $question = Question::where('id', $question_target)->first();
        $questions = Question::where('testable_type', $jenis_test)
        ->where('testable_id', $request->testID)
        // ->where('subject_id', '>=', $startSubjectID)
        // ->where('subject_id', '<=', $endSubjectID)
        ->get();
        return view('dashboard.nonpersonalisasi.test.test', [
            'title' => 'Pre test '.$request->title,
            'jenis_test' => $jenis_test,
            'testID' => $request->testID,
            'questions' => $questions,
            'result' => $result,
            'result_choose' => $result_choose,
            'question' => $question,
        ]);
    }

    public function Regulersubmit_test(Request $request, $jenis_test, $testID) 
    {
        $user_id = Auth::id();
        $user = auth()->user();
        $result = json_decode($request->input('result'), true);
        // Ambil semua modul berdasarkan classroom_id
        $subject_ids = Subject::where('classroom_id', $testID)->pluck('id');

        // Proses untuk mencari nilai tiap subject dan memasukkan data nya ke dalam tabel nilai_pelajars
        $questions = Question::where('testable_type', $jenis_test)
            ->where('testable_id', $testID)
            ->whereIn('subject_id', $subject_ids) // Menggunakan whereIn untuk mengambil pertanyaan dari modul yang sesuai
            ->get();
        
        $data = [];
        foreach ($questions as $question) {
            $id_question = $question->id;
            $benar_salah = $request->input('result.' . $id_question);
            $nilai = ($benar_salah === 'benar') ? 1 : 0; // Menggunakan ternary operator untuk menghitung nilai
            $subject_id = $question->subject_id; // Ambil subject_id dari question
            
            if (isset($data[$subject_id])) {
                $data[$subject_id]['nilai'] += $nilai;
            } else {
                $data[$subject_id] = [
                    'user_id' => $user_id,
                    'subject_id' => $subject_id,
                    'nilai' => $nilai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Simpan data nilai ke tabel
        DB::table('nilai_pelajars')->insert($data);

        // Hitung skor total
        $totalQuestions = count($result);
        $score = 0;
        foreach ($result as $answer) {
            if ($answer === "benar") {
                $score++;
            }
        }

        $skor = round(($score / $totalQuestions) * 100, 2);

        // Simpan hasil test
        $htp = new HasilTestPelajar;
        $htp->user_id = $user_id;
        $htp->testable_type = $jenis_test;
        $htp->testable_id = $testID;
        $htp->score = $skor;
        $htp->save();

        // Ambil data test sesuai dengan jenisnya
        $test = null;
        if ($jenis_test === 'pre-test') {
            $test = PreTest::find($testID);
        } elseif ($jenis_test === 'post-test') {
            $test = PostTest::find($testID);
        } else {
            $test = CourseTest::find($testID);
        }
    
        return view('dashboard.nonpersonalisasi.test.done', [
            'title' => 'Test',
            'jenis_test' => $jenis_test,
            'testID' => $testID,
            'nama_test' => $test->name,
            'skor' => $skor,
        ]);
    }

    public function Regulerclassroom_modul($subjectID)
    {
        $subject = Subject::find($subjectID);
        $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
            ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
            ->first();
        $classroom = Classroom::find($subject->classroom_id);
        $isModulTerbuka = $jalurPembelajaran->status === 'terbuka';
    
        // Memanggil kembali metode perhitungan ranking dari controller lain
        $resultcalculate = $this->calculateMatrix(); // Pastikan metode ini bisa diakses
        $ranking = $resultcalculate['ranking'];
        return view('dashboard.nonpersonalisasi.modul_class', [
            'title' => 'Pelajari Materi',
            'classroom' => $classroom,
            'subject' => $subject,
            'isModulTerbuka' => $isModulTerbuka,
            'ranking' => $ranking, // Mengirimkan ranking ke view
        ]);
    }
    public function startTimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $time = JalurPembelajaran::where('subject_id', $subject->id)->where('user_id', $User->id)->first();
        if (!$time) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $time->start_time = Carbon::now();
        $time->save();
        
        return response()->json(['message' => 'Timer started successfully']);
    }
    
    public function endTimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $timer = JalurPembelajaran::where('subject_id', $subject->id)
                ->where('user_id', $User->id)
                ->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $timer->end_time = Carbon::now();
        $timer->save();
    
        return response()->json(['message' => 'Timer started successfully']);
    }
    public function takenTimer($subjectID)
{
    $subject = Subject::find($subjectID);
    $user = Auth::user();
    $time = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', $user->id)
        ->first();

    if (!$time) {
        return response()->json(['error' => 'Timer not found'], 404);
    }

    // Pastikan end_time tidak kosong dan start_time sudah diisi sebelum menghitung taken time
        if ($time->start_time && $time->end_time) {
            $startTime = Carbon::parse($time->start_time);
            $endTime = Carbon::parse($time->end_time);

        // Hitung selisih waktu dalam menit
            $takenTime = $endTime->diffInMinutes($startTime);

        // Simpan taken time ke database
            $time->taken_time = $takenTime;
            $time->save();

            return response()->json(['message' => 'Taken time calculated and saved successfully']);
        } else {
            return response()->json(['error' => 'Both start time and end time must be set'], 400);
        }
    }
    public function pdf($subjectID){
        $subject = Subject::find($subjectID);
        $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
        ->first();
        $classroom = Classroom::find($subject->classroom_id);
        
    return view('dashboard.nonpersonalisasi.modul.pdf', [
        'title' => 'Pelajari Materi',
        'classroom' => $classroom,
        'subject' => $subject
    ]);
    }
    public function video_link($subjectID){
        $subject = Subject::find($subjectID);
        $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
        ->first();
        $classroom = Classroom::find($subject->classroom_id);
        
    return view('dashboard.nonpersonalisasi.modul.video', [
        'title' => 'Pelajari Materi',
        'classroom' => $classroom,
        'subject' => $subject
    ]);
    }

    public function interpreter($subjectID){
        $subject = Subject::find($subjectID);
        $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
        ->first();
        $classroom = Classroom::find($subject->classroom_id);
        
    return view('dashboard.nonpersonalisasi.modul.interpreter', [
        'title' => 'Pelajari Materi',
        'classroom' => $classroom,
        'subject' => $subject
    ]);
    }
    public function videostarttimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $time = JalurPembelajaran::where('subject_id', $subject->id)->where('user_id', $User->id)->first();
        if (!$time) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $time->starttime_video = Carbon::now();
        $time->save();
        
        return response()->json(['message' => 'Timer started successfully']);
    }
    
    public function videoendtimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $timer = JalurPembelajaran::where('subject_id', $subject->id)
                ->where('user_id', $User->id)
                ->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $timer->endtime_video = Carbon::now();
        $timer->save();
    
        return response()->json(['message' => 'Timer started successfully']);
    }
    public function videotakentimer($subjectID)
{
    $subject = Subject::find($subjectID);
    $user = Auth::user();
    $time = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', $user->id)
        ->first();

    if (!$time) {
        return response()->json(['error' => 'Timer not found'], 404);
    }

    // Pastikan end_time tidak kosong dan start_time sudah diisi sebelum menghitung taken time
        if ($time->starttime_video && $time->endtime_video) {
            $startTime = Carbon::parse($time->starttime_video);
            $endTime = Carbon::parse($time->endtime_video);

        // Hitung selisih waktu dalam menit
            $takenTime = $endTime->diffInMinutes($startTime);

        // Simpan taken time ke database
            $time->takentime_video = $takenTime;
            $time->save();

            return response()->json(['message' => 'Taken time calculated and saved successfully']);
        } else {
            return response()->json(['error' => 'Both start time and end time must be set'], 400);
        }
    }
    public function interpreterstarttimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $time = JalurPembelajaran::where('subject_id', $subject->id)->where('user_id', $User->id)->first();
        if (!$time) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $time->starttime_interpreter = Carbon::now();
        $time->save();
        
        return response()->json(['message' => 'Timer started successfully']);
    }
    
    public function interpreterendtimer($subjectID)
    {
        $subject = Subject::find($subjectID);
        $User = Auth::user();
        $timer = JalurPembelajaran::where('subject_id', $subject->id)
                ->where('user_id', $User->id)
                ->first();
        if (!$timer) {
            return response()->json(['error' => 'Timer not found'], 404); }
        $timer->endtime_interpreter = Carbon::now();
        $timer->save();
    
        return response()->json(['message' => 'Timer started successfully']);
    }
    public function interpretertakentimer($subjectID)
{
    $subject = Subject::find($subjectID);
    $user = Auth::user();
    $time = JalurPembelajaran::where('subject_id', $subject->id)
        ->where('user_id', $user->id)
        ->first();

    if (!$time) {
        return response()->json(['error' => 'Timer not found'], 404);
    }

    // Pastikan end_time tidak kosong dan start_time sudah diisi sebelum menghitung taken time
        if ($time->starttime_interpreter && $time->endtime_interpreter) {
            $startTime = Carbon::parse($time->starttime_interpreter);
            $endTime = Carbon::parse($time->endtime_interpreter);

        // Hitung selisih waktu dalam menit
            $takenTime = $endTime->diffInMinutes($startTime);

        // Simpan taken time ke database
            $time->takentime_interpreter = $takenTime;
            $time->save();

            return response()->json(['message' => 'Taken time calculated and saved successfully']);
        } else {
            return response()->json(['error' => 'Both start time and end time must be set'], 400);
        }
    }
    public function getModules(Request $request)
    {
        $startSubject = $request->input('start');
        $endSubject = $request->input('end');

        // Ambil data modul antara startSubject dan endSubject
        $modules = Subject::where('id', '>=', $startSubject)
                            ->where('id', '<=', $endSubject)
                            ->get();

        // Kirim data modul sebagai respons JSON
        return response()->json($modules);
    }
    public function saveModules(Request $request)
    {
        $startSubjectId = $request->input('startSubject');
        $endSubjectId = $request->input('endSubject');

        $user = auth()->user();

        $user->id_modul_awal_reguler = $startSubjectId;
        $user->id_modul_tujuan_reguler = $endSubjectId;
        $user->save();

        return redirect()->back()->with('success', 'Modul awal dan tujuan disimpan.');
    }    

    public function modul_sebelumnya($subjectID) {
        $subject = Subject::find($subjectID);
        $currentUser = Auth::user();
    
        if ($subject) {
            $currentJalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
                ->where('user_id', $currentUser->id)
                ->first();
    
            if ($currentJalurPembelajaran) {
                $previousJalurPembelajaranID = $currentJalurPembelajaran->id - 1;
                $previousJalurPembelajaran = JalurPembelajaran::find($previousJalurPembelajaranID);
    
                if ($previousJalurPembelajaran) {
                   
                    $previousSubject = $previousJalurPembelajaran->subject;
    
                    $classroom = Classroom::find($subject->classroom_id);
                    $isModulTerbuka = $previousJalurPembelajaran->status === 'terbuka';
    
                    return view('dashboard.nonpersonalisasi.modul_class', [
                        'title' => 'Pelajari Materi',
                        'classroom' => $classroom,
                        'subject' => $previousSubject,  
                        'isModulTerbuka' => $isModulTerbuka,
                        'jalurPembelajaran' => $previousJalurPembelajaran
                    ]);
                } else {
                    return redirect()->route('modul_terkunci');
                }
            } else {
                return redirect()->route('jalur_pembelajaran_tidak_ditemukan');
            }
        } else {
            return redirect()->route('subject_not_found');
        }
    }
    
    public function modul_pertama($subjectID) {
        $subject = Subject::find($subjectID);
        $currentUser = Auth::user();
        $firstJalurPembelajaran = JalurPembelajaran::where('user_id', Auth::id())->get()[0];
    
        if ($firstJalurPembelajaran) {
            $firstJalurPembelajaranID = $firstJalurPembelajaran->id;

    
            if ($firstJalurPembelajaranID) {
                $firstSubject = $firstJalurPembelajaran->subject;
                $classroom = Classroom::find($subject->classroom_id);
                $isModulTerbuka = $firstJalurPembelajaran->status === 'terbuka';
    
                return view('dashboard.nonpersonalisasi.modul_class', [
                    'title' => 'Pelajari Materi',
                    'classroom' => $classroom,
                    'subject' => $firstSubject,
                    'isModulTerbuka' => $isModulTerbuka,
                    'jalurPembelajaranID' => $firstJalurPembelajaranID
                ]);
            }
        }
    
        return redirect()->route('modul_terkunci');
    }

    

    public function Regulersubjecttest_do($jenis_test, $subjectID)
    {
        $subject = Subject::where('id',$subjectID)->first();
        $testID = CourseTest::where('subject_id', $subjectID)->first()->id;
        $questions = Question::where('testable_type', $jenis_test)->where('testable_id', $testID)->get();
        $result = array();
        $result_choose = array();
            foreach ($questions as $key) {
                $result[$key->id] = null;
            }
            return view('dashboard.nonpersonalisasi.test.coursetest', [
                'title' => $jenis_test.' '.$subject->name,
            // 'alert' => 'Ujian akan dimulai, mohon kerjakan dengan sungguh-sungguh dan jujur.',
                'jenis_test' => $jenis_test,
                'testID' => $testID,
                'questions' => $questions,
                'result' => $result,
                'result_choose' => $result_choose,
                'question' => $questions[0],
                'subject' => $subjectID,
            ]); 
        }

    
        

    public function Regulersubjecttest (Request $request, $jenis_test, $question_target)
    {
        $result = json_decode($request->input('result'), true);
        $result_choose = json_decode($request->input('result_choose'), true);
        if ($request->answer) {
            $result[$request->question_id] = Answer::find($request->answer)->benar_salah;
            $result_choose[$request->question_id] = $request->answer;
        }
        
        $question = Question::where('id', $question_target)->first();
        $questions = Question::where('testable_type', $jenis_test)->where('testable_id', $request->testID)->get();
        return view('dashboard.nonpersonalisasi.test.coursetest', [
            'title' => 'Course test '.$request->title,
            'jenis_test' => $jenis_test,
            'testID' => $request->testID,
            'questions' => $questions,
            'result' => $result,
            'result_choose' => $result_choose,
            'question' => $question,
        ]);
    }
    
    
    public function Regulersubmit_subjecttest (Request $request, $jenis_test, $testID) 
    {
        $user_id = Auth::id();
        
        $result = json_decode($request->input('result'), true);

        $questions = new Question;
        $data = [];
        foreach ($result as $id_question => $benar_salah) {
            $nilai = 0;
            if ($benar_salah === 'benar') {
                $nilai += 1;
            }
            $subject_id = $questions->find($id_question)->subject_id;
            
            if (isset($data[$subject_id])) {
                $data[$subject_id]['nilai'] += $nilai;
            } else {
                $item = [
                    'user_id' => $user_id,
                    'subject_id' => $subject_id,
                    'nilai' => $nilai,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $data[$subject_id] = $item;
            }
        }
        DB::table('nilai_pelajars')->insert($data);
        
        $totalQuestions = count($result);
        $score = 1;
        foreach ($result as $questionId => $answer) {
            if ($answer === "benar") {
                $score += 1;
            }
        }
        $skor = round(($score / $totalQuestions) * 100, 2);
        if ($skor >= 70) {
            $subjectID = $subject_id;
            $subject = Subject::find($subjectID);
            $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
            ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
            ->first();
            $currentId = $jalurPembelajaran -> id+1;
            $modul = JalurPembelajaran::find($currentId);
            if($modul){
                $result = $modul->update(['status' => 'terbuka']);
            } else {
                $htp = new HasilTestPelajar;
                $htp->user_id = $user_id;
                $htp->testable_type = $jenis_test;
                $htp->testable_id = $testID;
                $htp->score = $skor;
                $htp->save();
            }
        } 
        $htp = new HasilTestPelajar;
        $htp->user_id = $user_id;
        $htp->testable_type = $jenis_test;
        $htp->testable_id = $testID;
        $htp->score = $skor;
        $htp->save();

        
        $test = null;
        if ($jenis_test === 'course-test') {
            $test = CourseTest::find($testID);
        // } else {
        //     $test = PostTest::find($testID);
        }
        
        return view('dashboard.nonpersonalisasi.test.donecoursetest', [
            'title' => 'Test',
            'jenis_test' => $jenis_test,
            'nama_test' => $test->name,
            'skor' => $skor,
        ]);
       
    } 


    public function Regulerclassroom_out($classroomID)
    {
        // buat logika keluar dari kelas, dengan menghapus data di kelas_siswas dengan ciri user auth sekarang dan classroomID
        $userId = Auth::id();
        $kelas_siswa = KelasSiswa::where('user_id', $userId)->where('classroom_id', $classroomID)->first();
        $kelas_siswa->delete();
        $my_class = User::find($userId)->kelasSiswa;
        return redirect()->route('reguler.my-class')->with([
            'title' => 'Dashboard pelajar reguler',
            'classrooms' => $my_class,
             'myClassrooms' => null
]);

    }
    public function susun_jalur_pembelajaran($classroomID)
    {
        $user_id = Auth::id();
        $user = auth()->user();
    
        // Ambil modul yang sesuai dengan classroom_id pengguna
        $subject_ids = Subject::where('classroom_id', $classroomID)->pluck('id');
    
        foreach ($subject_ids as $index => $subject_id) {
            $jalur_pembelajaran = new JalurPembelajaran;
            $jalur_pembelajaran->user_id = $user_id;
            $jalur_pembelajaran->subject_id = $subject_id;
    
            // Set status modul pertama terbuka, lainnya terkunci
            $jalur_pembelajaran->status = 'terbuka';
    
            $jalur_pembelajaran->save();
        }
        
        return redirect()->route('reguler.my-class');
    }
    

    }