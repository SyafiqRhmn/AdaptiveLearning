<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Answer;
use App\Models\PreTest;
use App\Models\Subject;
use App\Models\PostTest;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\KelasSiswa;
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
        $userId = Auth::id();
        $my_class = User::find($userId)->kelasSiswa;
        return view('dashboard.nonpersonalisasi.index', [
            'title' => 'Dashboard pelajar reguler',
            'classrooms' => $my_class,
            'myClassrooms' => null
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
        $startSubject = $user->id_modul_awal_reguler;
        $subjects = JalurPembelajaran::where('user_id', Auth::id())  
        ->whereHas('subject', function ($query) use ($classroomID) {
        $query->where('classroom_id', $classroomID); })->orderBy('id')->get();
        if ($subjects->isEmpty()) {
            $subjects = Subject::where('classroom_id', $classroomID)->get();
        }
        $hasilTestPelajar = HasilTestPelajar::where('user_id', Auth::user()->id)->where('testable_type', 'pre-test')->get();
        $test = null;
        if ($hasilTestPelajar !== null) {
            foreach ($hasilTestPelajar as $htp) {
                echo $htp->classroom_id;
                $pt = PreTest::where('id', $htp->testable_id)->first();
                if ($pt !== null && $pt->classroom_id == $classroomID) {
                    $test = $pt;
                }
            }
        }
        return view('dashboard.nonpersonalisasi.class', [
            'title' => $class->name,
            'classroomID' => $classroomID,
            'test' => $test,
            'class' => $class,
            'subjects' => $subjects,
            'startSubject'=> $startSubject,
        ]);
    }

    public function Regulertest_do($jenis_test, $classroomID)
    {
        $class = Classroom::where('id', $classroomID)->first();
        $testID = PreTest::where('id', $classroomID)->first()->id;
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_reguler;
        $endSubjectID = $user->id_modul_tujuan_reguler;
        $questions = Question::where('testable_type', $jenis_test)
        ->where('subject_id', '>=', $startSubjectID)
        ->where('subject_id', '<=', $endSubjectID)
        ->where('testable_id', $testID)
        ->get();
        $result = array();
        $result_choose = array();
            foreach ($questions as $key) {
             $result[$key->id] = null;
             }
            return view('dashboard.nonpersonalisasi.test.test', [
            'title' => $jenis_test.' '.$class->name,
             // 'alert' => 'Ujian akan dimulai, mohon kerjakan dengan sungguh-sungguh dan jujur.',
            'jenis_test' => $jenis_test,
            'testID' => $testID,
            'questions' => $questions,
            'result' => $result,
            'result_choose' => $result_choose,
            'question' => $questions[0],
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
        $startSubjectID = $user->id_modul_awal_reguler;
        $endSubjectID = $user->id_modul_tujuan_reguler;
        $question = Question::where('id', $question_target)->first();
        $questions = Question::where('testable_type', $jenis_test)
        ->where('testable_id', $request->testID)
        ->where('subject_id', '>=', $startSubjectID)
        ->where('subject_id', '<=', $endSubjectID)
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

    public function Regulersubmit_test (Request $request, $jenis_test, $testID) 
    {
        $user_id = Auth::id();
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_reguler;
        $endSubjectID = $user->id_modul_tujuan_reguler;
        $result = json_decode($request->input('result'), true);

        // proses untuk mencari nilai tiap subject dan memasukkan data nya ke dalam tabel nilai_pelajars
        $questions = Question::where('testable_type', $jenis_test)
        ->where('testable_id', $request->testID)
        ->where('subject_id', '>=', $startSubjectID)
        ->where('subject_id', '<=', $endSubjectID)
        ->get();
        $data = [];
        foreach ($questions as $question) {
            $id_question = $question->id;
            $benar_salah = $request->input('result.' . $id_question);
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
        $score = 0;
        foreach ($result as $questionId => $answer) {
            if ($answer === "benar") {
                $score += 1;
            }
        }
        $skor = round(($score / $totalQuestions) * 100, 2);
        
        $htp = new HasilTestPelajar;
        $htp->user_id = $user_id;
        $htp->testable_type = $jenis_test;
        $htp->testable_id = $testID;
        $htp->score = $skor;
        $htp->save();

        $test = null;
        if ($jenis_test === 'pre-test') {
            $test = PreTest::find($testID);
        }elseif($jenis_test === 'post-test'){
            $test = PostTest::find($testID);
        } else {
            $test = CourseTest::find($testID);
        }
        
        return view('dashboard.nonpersonalisasi.test.done', [
            'title' => 'Test',
            'jenis_test' => $jenis_test,
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
    
        return view('dashboard.nonpersonalisasi.modul_class', [
            'title' => 'Pelajari Materi',
            'classroom' => $classroom,
            'subject' => $subject,
            'isModulTerbuka' => $isModulTerbuka,
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
    public function susun_jalur_pembelajaran()
{
    $user_id = Auth::id();
    $user = auth()->user();
    $startSubjectID = $user->id_modul_awal_reguler;
    $endSubjectID = $user->id_modul_tujuan_reguler;
    $subject_ids = Subject::where('id', '>=', $startSubjectID)
    ->where('id', '<=', $endSubjectID)
    ->pluck('id');

   
    foreach ($subject_ids as $index => $subject_id) {
        $jalur_pembelajaran = new JalurPembelajaran;
        $jalur_pembelajaran->user_id = $user_id;
        $jalur_pembelajaran->subject_id = $subject_id;
        

        if ($index === 0) {
            $jalur_pembelajaran->status = 'terbuka'; // Modul pertama
        } else {
            $jalur_pembelajaran->status = 'terkunci';
        }

        $jalur_pembelajaran->save();
    }
    return redirect()->route('reguler.my-class');
}

    }