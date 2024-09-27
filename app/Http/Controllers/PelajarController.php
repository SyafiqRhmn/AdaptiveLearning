<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Answer;
use App\Models\PreTest;
use App\Models\Subject;
use App\Models\PostTest;
use App\Models\CourseTest;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\KelasSiswa;
use App\Models\NilaiPelajar;
use Illuminate\Http\Request;
use App\Libraries\PythonWrapper;
use App\Models\HasilTestPelajar;
use App\Models\JalurPembelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PelajarController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $my_class = User::find($userId)->kelasSiswa;
        return view('dashboard.pelajar.index', [
            'title' => 'Dashboard pelajar',
            'classrooms' => $my_class,
            'myClassrooms' => null
        ]);
    }

    public function setting_akun()
    {
        $userId = Auth::id();
        $myprofile = User::find($userId);
        return view('dashboard.pelajar.setting.setting_akun',[
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
        return redirect()->route('adaptive.settingAkun')->with('success', 'Profil berhasil diperbarui');
    }

    return view('dashboard.pelajar.setting.setting_akun', [
        'title' => 'Setting Akun',
        'myprofile' => $myprofile
    ]);
}
    public function allClass ()
    {
        $my_class = User::find(Auth::id())->kelasSiswa;
        $my_classes = $my_class->pluck('classroom_id')->toArray();
        return view('dashboard.pelajar.all_class', [
            'title' => 'Dashboard pelajar',
            'classrooms' => Classroom::paginate(10),
            'my_classes' => $my_classes,
        ]);
    }
    
    public function ikutiKelas ($classroomID)
    {
        $KelasSiswa = new KelasSiswa;
        $KelasSiswa->user_id = Auth::id();
        $KelasSiswa->classroom_id = $classroomID;
        $KelasSiswa->save();

        $userId = Auth::id();
        $my_class = User::find($userId)->kelasSiswa;
        return redirect()->route('adaptive.my-class')->with([
            'title' => 'Dashboard pelajar',
            'classrooms' => $my_class,
            'myClassrooms' => null
        ]);
    }
    
    public function my_class ($classroomID)
    {
        $class = Classroom::where('id', $classroomID)->get()[0];
        $user = auth()->user();
        $startSubject = $user->id_modul_awal_adaptive;
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

        return view('dashboard.pelajar.class', [
            'title' => $class->name,
            'classroomID' => $classroomID,
            'test' => $test,
            'class' => $class,
            'subjects' => $subjects,
            'startSubject'=> $startSubject,
        ]);
    }
    

    public function test_do($jenis_test, $classroomID)
    {
        $class = Classroom::where('id', $classroomID)->first();
        $testID = PreTest::where('id', $classroomID)->first()->id;
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_adaptive;
        $endSubjectID = $user->id_modul_tujuan_adaptive;
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

        return view('dashboard.pelajar.test.test', [
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
    public function test_doposttest($jenis_test, $classroomID)
    {
        $class = Classroom::where('id', $classroomID)->first();
        $testID = PostTest::where('id', $classroomID)->first()->id;
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_adaptive;
        $endSubjectID = $user->id_modul_tujuan_adaptive;
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

        return view('dashboard.pelajar.test.test', [
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


    public function test (Request $request, $jenis_test, $question_target)
    {
        $result = json_decode($request->input('result'), true);
        $result_choose = json_decode($request->input('result_choose'), true);
        if ($request->answer) {
            $result[$request->question_id] = Answer::find($request->answer)->benar_salah;
            $result_choose[$request->question_id] = $request->answer;
        }
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_adaptive;
        $endSubjectID = $user->id_modul_tujuan_adaptive;
        $question = Question::where('id', $question_target)->first();
        $questions = Question::where('testable_type', $jenis_test)
        ->where('testable_id', $request->testID)
        ->where('subject_id', '>=', $startSubjectID)
        ->where('subject_id', '<=', $endSubjectID)
        ->get();
        return view('dashboard.pelajar.test.test', [
            'title' => 'Pre test '.$request->title,
            'jenis_test' => $jenis_test,
            'testID' => $request->testID,
            'questions' => $questions,
            'result' => $result,
            'result_choose' => $result_choose,
            'question' => $question,
        ]);
    }

    public function submit_test (Request $request, $jenis_test, $testID) 
    {
        $user_id = Auth::id();
        $user = auth()->user();
        $startSubjectID = $user->id_modul_awal_adaptive;
        $endSubjectID = $user->id_modul_tujuan_adaptive;
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
        
        return view('dashboard.pelajar.test.done', [
            'title' => 'Test',
            'jenis_test' => $jenis_test,
            'nama_test' => $test->name,
            'skor' => $skor,
        ]);
    }

    public function classroom_modul($subjectID)
{
    $subject = Subject::find($subjectID);
    $jalurPembelajaran = JalurPembelajaran::where('subject_id', $subject->id)
    ->where('user_id', Auth::id()) // Menambahkan filter berdasarkan user yang terautentikasi
    ->first();
    $classroom = Classroom::find($subject->classroom_id);
    $isModulTerbuka = $jalurPembelajaran->status === 'terbuka';

    return view('dashboard.pelajar.modul_class', [
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
        
    return view('dashboard.pelajar.modul.pdf', [
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
        
    return view('dashboard.pelajar.modul.video', [
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
        
    return view('dashboard.pelajar.modul.interpreter', [
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

        $user->id_modul_awal_adaptive = $startSubjectId;
        $user->id_modul_tujuan_adaptive = $endSubjectId;
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
    
                    return view('dashboard.pelajar.modul_class', [
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
    
                return view('dashboard.pelajar.modul_class', [
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
    
    
    

    public function subjecttest_do($jenis_test, $subjectID)
    {
        $subject = Subject::where('id',$subjectID)->first();
        $testID = CourseTest::where('subject_id', $subjectID)->first()->id;
        $questions = Question::where('testable_type', $jenis_test)->where('testable_id', $testID)->get();
        $result = array();
        $result_choose = array();
            foreach ($questions as $key) {
                $result[$key->id] = null;
            }
            return view('dashboard.pelajar.test.coursetest', [
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

    public function subjecttest (Request $request, $jenis_test, $question_target)
    {
        $result = json_decode($request->input('result'), true);
        $result_choose = json_decode($request->input('result_choose'), true);
        if ($request->answer) {
            $result[$request->question_id] = Answer::find($request->answer)->benar_salah;
            $result_choose[$request->question_id] = $request->answer;
        }
        
        $question = Question::where('id', $question_target)->first();
        $questions = Question::where('testable_type', $jenis_test)->where('testable_id', $request->testID)->get();
        return view('dashboard.pelajar.test.coursetest', [
            'title' => 'Course test '.$request->title,
            'jenis_test' => $jenis_test,
            'testID' => $request->testID,
            'questions' => $questions,
            'result' => $result,
            'result_choose' => $result_choose,
            'question' => $question,
        ]);
    }
    
    
    public function submit_subjecttest (Request $request, $jenis_test, $testID) 
    {
        $user_id = Auth::id();
        
        $result = json_decode($request->input('result'), true);

        // proses untuk mencari nilai tiap subject dan memasukkan data nya ke dalam tabel nilai_pelajars
        $questions = new Question;
        $data = [];
        foreach ($result as $id_question => $benar_salah) {
            $nilai = 1;
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
        
        return view('dashboard.pelajar.test.donecoursetest', [
            'title' => 'Test',
            'jenis_test' => $jenis_test,
            'nama_test' => $test->name,
            'skor' => $skor,
        ]);
       
    } 

    public function classroom_out($classroomID)
    {
        // buat logika keluar dari kelas, dengan menghapus data di kelas_siswas dengan ciri user auth sekarang dan classroomID
        $userId = Auth::id();
        $kelas_siswa = KelasSiswa::where('user_id', $userId)->where('classroom_id', $classroomID)->first();
        $kelas_siswa->delete();
        $my_class = User::find($userId)->kelasSiswa;
        return redirect()->route('adaptive.my-class')->with([
            'title' => 'Dashboard pelajar',
            'classrooms' => $my_class,
            'myClassrooms' => null
        ]);
    }

    public function jalur_pembelajaran()
    {
        return view('dashboard.pelajar.jalur_belajar', [
            'title' => 'Susun Jalur Belajar',
            
        ]);
    }

    function double_point_crossover($parent1, $parent2) {

        $crossover_points = array_rand(range(1, count($parent1)), 2);
        sort($crossover_points);
    

        $child1 = array_fill(0, count($parent1), null);
        $child2 = array_fill(0, count($parent1), null);
    
        
        for ($i = $crossover_points[0]; $i < $crossover_points[1]; $i++) {
            $child1[$i] = $parent1[$i];
            $child2[$i] = $parent2[$i];
        }
    
        $child1 = $this->fill_child($child1, $parent2, $crossover_points);
        $child2 = $this->fill_child($child2, $parent1, $crossover_points);
    
        return [$child1, $child2];
    }
    
    function fill_child($child, $parent, $crossover_points) {
        
        $parent_index = 0;
        for ($child_index = 0; $child_index < count($child); $child_index++) {
            if ($child[$child_index] === null) {
                while (in_array($parent[$parent_index], $child)) {
                    $parent_index++;
                }
                $child[$child_index] = $parent[$parent_index];
                $parent_index++;
            }
        }
        
        return $child;
    }
    
    function scramble_mutation($individual, $mutation_rate) {
        $mutated_individual = $individual;
    
       
        $pos1 = rand(0, count($individual) - 1);
        $pos2 = rand(0, count($individual) - 1);
    

        if (rand() / getrandmax() < $mutation_rate) {

            $sublist = array_slice($mutated_individual, min($pos1, $pos2), max($pos1, $pos2) - min($pos1, $pos2) + 1);
  
            shuffle($sublist);
    

            array_splice($mutated_individual, min($pos1, $pos2), max($pos1, $pos2) - min($pos1, $pos2) + 1, $sublist);
        }
    
        return $mutated_individual;
    }    

    public function susun_jalur_pembelajaran()
    {

        $user_id = Auth::id();
        $nilaiPelajars = new NilaiPelajar;
        $subjects = new Subject;
        
        $populasi1 = [];
        $user1 = $nilaiPelajars->where('user_id', $user_id)->get()->sortByDesc('nilai')->values()->all();
        $subject_id = 17;
        $testable_type = 'pre-test';
        $nilai_tertinggi = Question::where('subject_id', $subject_id)
        ->where('testable_type', $testable_type)
        ->count();
        foreach ($user1 as $key) {
           
            $nama_subject = $subjects->find($key->subject_id)->name;
            
            if ($nilai_tertinggi !== $key->nilai) {
                $populasi1[$key->subject_id] = $nama_subject;
                
            }
        }
       

       
        $populasi2 = [];
        $class_id_subject = $nilaiPelajars->where('user_id', $user_id)->latest()->first()->subject->classroom_id;
        foreach ($subjects::where('classroom_id', $class_id_subject)->get() as $subject) {
            $nilai = $nilaiPelajars->where('subject_id', $subject->id)->get();
            $total_nilai = $nilai->pluck('nilai')->sum();
            $subject_id = 17;
            $testable_type = 'pre-test';
            $nilai_tertinggi = Question::where('subject_id', $subject_id)
            ->where('testable_type', $testable_type)
            ->count();
            $siswa_yg_mengerjakan = $nilai->count();
            $tingkat_kesulitan = $total_nilai/($nilai_tertinggi * $siswa_yg_mengerjakan+1);
            $nilai_fitness = 1*(1-$tingkat_kesulitan);
            $populasi2[$subject->id] = $nilai_fitness;
        }
        asort($populasi2);
        


        $id_populasi1 = [];
        $i = 0;
        foreach ($populasi1 as $key => $value) {
            $id_populasi1[$i] = $key;
            $i++;
        }
        
        $id_populasi2 = [];
        $i = 0;
        foreach ($populasi2 as $key => $value) {
            $id_populasi2[$i] = $key;
            $i++;
        }
        
    
        for ($j=1; $j <= 100; $j++) {

            $populasi = $this->double_point_crossover($id_populasi1, $id_populasi2);

            
            $individual1 = $populasi[0];
            $individual2 = $populasi[1];
            $mutuation_rate = 0.2;

            $mutuation_individual1 = $this->scramble_mutation($individual1, $mutuation_rate);
            $mutuation_individual2 = $this->scramble_mutation($individual2, $mutuation_rate);
            $id_populasi1 = $mutuation_individual1;
            $id_populasi2 = $mutuation_individual2;
        }
        $message = '';
        foreach ($mutuation_individual1 as $key => $value) {
            $user = auth()->user();
            $startSubjectID = $user->id_modul_awal_adaptive;
            $endSubjectID = $user->id_modul_tujuan_adaptive;
            $jalur_pembelajaran = new JalurPembelajaran;
            $jalur_pembelajaran->user_id = $user_id;
            $jalur_pembelajaran->subject_id = $value;
            if ($value >= $startSubjectID && $value <= $endSubjectID){
                $jalur_pembelajaran->save();
                $subject = $subjects::find($value);
                if ($key === 0) { // Ini materi pertama
                    $jalur_pembelajaran->status = 'terbuka';
                } else {
                    $jalur_pembelajaran->status = 'terkunci';
                    }
                $jalur_pembelajaran->save();
                $message .= "{$subject->name}, ";
            }else{
                continue;
                }
            }
        $message = rtrim($message, ', ');

        $data = [
            'message' => $message
        ];

       
        return response()->json($data);
    }
}
