<?php

use App\Models\User;
use App\Models\Classroom;
use App\Models\HasilTestPelajar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PelajarController;
use App\Http\Controllers\PreTestController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\CourseTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\QuesionerPelajarController;
use App\Http\Controllers\QuesionerGuruController;
use App\Http\Controllers\SoalJabawanController;
use App\Http\Controllers\SoalJawabanController;
use App\Http\Controllers\PelajarRegulerController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index', ['title' => 'Home']);
})->middleware('guest');

Route::get('/home', function () {
    if (Auth::user()->role === 'admin') {
        return view('dashboard.user.index', ['title' => 'Data User', 'users' => User::paginate(10)]);;
    }
    elseif (Auth::user()->role === 'guru') {
        return redirect()->route('classroom.index');
    }
    elseif (Auth::user()->role === 'pelajar') {
        if (Auth::user()->tipe === 'reguler') {
            return redirect('/reguler/dashboard/nonpersonalisasi');
        }        
        else {
            return redirect('/adaptive/dashboard');
        }
        
    }
});


Route::get('/login', [AuthController::class, 'login_view'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login_action'])->middleware('guest');


Route::get('/register', [AuthController::class, 'register_view'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register_action'])->middleware('guest');

Route::get('/quis', [AuthController::class, 'quis_view'])->name('quis')->middleware('guest');

// route grup sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function (){
        if (Auth::user()->role === 'guru') {
            return redirect()->route('classroom.index');
        }
        if (Auth::user()->role === 'pelajar') {
            if (Auth::user()->tipe == 'adaptive'){
                return view('dashboard.index', [
                    'title' => 'Dashboard pelajar',
                    'classrooms' => Classroom::paginate(10),
                    'myClassrooms' => Classroom::paginate(10),
                ]);}
            elseif (Auth::user()->tipe == 'reguler'){
                return view('dashboard.nonpersonalisasi.index',[
                    'title' => 'Dashboard pelajar',
                    'classrooms' => Classroom::paginate(10),
                    'myClassrooms' => Classroom::paginate(10)
                ]);
            }
        }
        return view('dashboard.option', ['title' => 'Dashboard']);
    });
    Route::get('/logout', [AuthController::class, 'logout']);
    // route grup guru
    Route::middleware(['guru'])->group(function () {
        Route::resource('/quesioner/qu-guru', QuesionerGuruController::class);
        Route::resource('/quesioner/qu-pelajar', QuesionerPelajarController::class);
        Route::resource('/classroom', ClassroomController::class);
        Route::resource('/subject', SubjectController::class);
        Route::resource('/test/pre-test', PreTestController::class);
        Route::resource('/test/post-test', PostTestController::class);
        Route::resource('/test/course-test',CourseTestController::class);
        Route::resource('/test/question', QuestionController::class);
        Route::resource('/test/answer', AnswerController::class);
        Route::resource('/test/soal-jawaban', SoalJawabanController::class);
        Route::get('/hasil-test-pelajar', function () {
            return view('dashboard.hasil_test_pelajar', [
                'title' => 'Hasil Test Pelajar',
                'HTPs' => HasilTestPelajar::paginate(10)
            ]);
        });
        Route::get('/hasil-test-pelajar', function () {
            $query = request('q'); // Get the value from the search input
            $filterKelas = request('Kelas');
            $filterProdi = request('prodi');
            $filterModul = request('Modul');
            $queryBuilder = HasilTestPelajar::query();
        // $HTPs = HasilTestPelajar::whereHas('user', function ($queryBuilder) use ($query) {
        //     $queryBuilder->where('name', 'like', '%' . $query . '%');
        // })
        // ->paginate(10);
            if ($query) {
                $queryBuilder->whereHas('user', function ($userQuery) use ($query) {
                    $userQuery->where('name', 'like', '%' . $query . '%');
                });
            }
            if ($filterKelas) {
                $queryBuilder->whereHas('user', function ($userQuery) use ($filterKelas) {
                    $userQuery->where('kelas', 'like', '%' . $filterKelas . '%');
                });
            }
            if ($filterProdi) {
                $queryBuilder->whereHas('user', function ($userQuery) use ($filterProdi) {
                    $userQuery->where('program_studi', 'like', '%' . $filterProdi . '%');
                });
            }
        // if ($filterModul) {
        //     $queryBuilder->whereHas('testable', function ($testableQuery) use ($filterModul) {
        //         $testableQuery->where('testable_id', $filterModul)
        //                       ->where(function ($query) {
        //                           $query->where('testable_type', 'App\Models\PreTest')
        //                                 ->orWhere('testable_type', 'App\Models\PostTest')
        //                                 ->orWhere('testable_type', 'App\Models\CourseTest');
        //                       });
        //     });
        // }
        
            $HTPs = $queryBuilder->paginate(10000);
            return view('dashboard.hasil_test_pelajar', [
                'title' => 'Hasil Test Pelajar',
                'HTPs' => $HTPs,
                'query' => $query,
            ]);
        });
    });


     // route grup admin
     Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [UserController::class, 'index']);
        Route::get('/user/pelajar', [UserController::class, 'pelajar']);
        Route::get('/user/guru', [UserController::class, 'guru']);
        Route::get('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('reset.password');
        Route::resource('/user', UserController::class);
    });

   
    
    // route pelajar non adaptive
    Route::middleware(['pelajarnonpersonalisasi'])->prefix('reguler')->group(function () {
        Route::get('/dashboard/nonpersonalisasi', [PelajarRegulerController::class, 'index']);
        Route::get('/kuisioner', [PelajarRegulerController::class, 'index'])->name('reguler.kuisioner');
        Route::get('/my-class', [PelajarRegulerController::class, 'my_class'])->name('reguler.my-class');
        Route::get('/my-class/{classroom}', [PelajarRegulerController::class, 'my_classReguler'])->name('reguler.my-class.classroom');
        Route::get('/all-class', [PelajarRegulerController::class, 'allClassReguler'])->name('reguler.all-class');
        Route::get('/all-class/{classroom}/ikuti', [PelajarRegulerController::class, 'ikutiKelasReguler'])->name('reguler.all-class.ikuti');
        Route::get('test/do/{test}/{classroomID}', [PelajarRegulerController::class, 'Regulertest_do'])->name('reguler.test.do');
        Route::post('test/{test}/{questID}/question', [PelajarRegulerController::class, 'Regulertest'])->name('reguler.test.question');
        Route::post('/test/{test}/{testID}', [PelajarRegulerController::class, 'Regulersubmit_test'])->name('reguler.test.submit');
        Route::get('/classroom/modul/{subjectID}', [PelajarRegulerController::class, 'Regulerclassroom_modul'])->name('reguler.classroom.modul');
        Route::get('subjecttest/do/{test}/{subjectID}', [PelajarRegulerController::class, 'Regulersubjecttest_do'])->name('reguler.subjecttest.do');
        Route::post('subjecttest/{test}/{questID}/question', [PelajarRegulerController::class, 'Regulersubjecttest'])->name('reguler.subjecttest.question');
        Route::post('subjecttest/{test}/{testID}', [PelajarRegulerController::class, 'Regulersubmit_subjecttest'])->name('reguler.subjecttest.submit');
        Route::get('/classroom/{classroomID}/out', [PelajarRegulerController::class, 'Regulerclassroom_out'])->name('reguler.classroom.out');
        Route::get('/susun-jalur-pembelajaran', [PelajarRegulerController::class, 'susun_jalur_pembelajaran'])->name('reguler.susun-jalur-pembelajaran');
        Route::get('/get-modules', [PelajarRegulerController::class, 'getModules'])->name('reguler.getmodules');
        Route::put('/save-modules', [PelajarRegulerController::class, 'saveModules'])->name('reguler.save_modules');
        Route::get('/pelajari-pdf/{subjectID}',[PelajarRegulerController::class,'pdf'])->name('reguler.pdf');
        Route::get('/pelajari-video/{subjectID}',[PelajarRegulerController::class,'video_link'])->name('reguler.video_link');
        Route::get('/pelajari-interpreter/{subjectID}',[PelajarRegulerController::class,'interpreter'])->name('reguler.interpreter');
        Route::get('/modul-sebelumnya/{subjectID}',[PelajarRegulerController::class,'modul_sebelumnya'])->name('reguler.modulsebelumnya');
        Route::get('/modul-pertama/{subjectID}',[PelajarRegulerController::class,'modul_pertama'])->name('reguler.modulpertama');
        Route::get('/setting-akun',[PelajarRegulerController::class,'setting_akun'])->name('reguler.settingAkun');
        Route::put('/edit-akun/{userID}',[PelajarRegulerController::class,'edit_akun'])->name('reguler.editAkun');
        Route::put('/start-timer/{subjectID}',[PelajarRegulerController::class,'startTimer'])->name('reguler.starttimerpdf');
        Route::put('/start-timer-video/{subjectID}',[PelajarRegulerController::class,'videostarttimer'])->name('reguler.starttimervideo');
        Route::put('/start-timer-interpreter/{subjectID}',[PelajarRegulerController::class,'interpreterstarttimer'])->name('reguler.starttimerinterpreter');     
        Route::put('/end-timer/{subjectID}',[PelajarRegulerController::class,'endTimer'])->name('reguler.endtimerpdf');
        Route::put('/end-timer-video/{subjectID}',[PelajarRegulerController::class,'videoendtimer'])->name('reguler.endtimervideo');
        Route::put('/end-timer-interpreter/{subjectID}',[PelajarRegulerController::class,'interpreterendtimer'])->name('reguler.endtimerinterpreter');
        Route::put('/taken-timer/{subjectID}',[PelajarRegulerController::class,'takenTimer'])->name('reguler.endtimerpdf');
        Route::put('/taken-timer-video/{subjectID}',[PelajarRegulerController::class,'videotakentimer'])->name('reguler.takentimervideo');
        Route::put('/taken-timer-interpreter/{subjectID}',[PelajarRegulerController::class,'interpretertakentimer'])->name('reguler.takentimerinterpreter');
        Route::get('/hasil-test-saya', function () {
             $user = Auth::user();
             $HTPs = HasilTestPelajar::where('user_id', $user->id)->paginate(10);
                return view('dashboard.hasil_test_saya', [
                    'title' => 'Hasil Test Saya',
                    'HTPs' => $HTPs
                     ]);
                 });
            });
//     // route grup untuk pelajar adaptive
    Route::middleware(['pelajar'])->prefix('adaptive')->group(function () {
        Route::get('/dashboard', [PelajarController::class, 'index']);
        Route::get('/my-class', [PelajarController::class, 'index'])->name('adaptive.my-class');
        Route::get('/my-class/{classroom}', [PelajarController::class, 'my_class'])->name('adaptive.my-class.classroom');
        Route::get('/all-class', [PelajarController::class, 'allClass'])->name('adaptive.all-class');
        Route::get('/all-class/{classroom}/ikuti', [PelajarController::class, 'ikutiKelas'])->name('adaptive.all-class.ikuti');
        Route::get('test/do/{test}/{classroomID}', [PelajarController::class, 'test_do'])->name('adaptive.test.do');
        Route::post('test/{test}/{questID}/question', [PelajarController::class, 'test'])->name('adaptive.test.question');
        Route::post('/test/{test}/{testID}', [PelajarController::class, 'submit_test'])->name('adaptive.test.submit');
        Route::get('/classroom/modul/{subjectID}', [PelajarController::class, 'classroom_modul'])->name('adaptive.classroom.modul');
        Route::get('/classroom/{classroomID}/out', [PelajarController::class, 'classroom_out'])->name('adaptive.classroom.out');
        Route::get('subjecttest/do/{test}/{subjectID}', [PelajarController::class, 'subjecttest_do'])->name('adaptive.subjecttest.do');
        Route::post('subjecttest/{test}/{questID}/question', [PelajarController::class, 'subjecttest'])->name('adaptive.subjecttest.question');
        Route::post('/subjecttest/{test}/{testID}', [PelajarController::class, 'submit_subjecttest'])->name('adaptive.subjecttest.submit');
        Route::get('/jalur-pembelajaran', [PelajarController::class, 'jalur_pembelajaran'])->name('adaptive.jalur-pembelajaran');
        Route::get('/susun-jalur-pembelajaran', [PelajarController::class, 'susun_jalur_pembelajaran'])->name('adaptive.susun-jalur-pembelajaran');
        Route::get('/get-modules', [PelajarController::class, 'getModules'])->name('adaptive.getmodules');
        Route::put('/save-modules', [PelajarController::class, 'saveModules'])->name('adaptive.save_modules');
        Route::get('/pelajari-pdf/{subjectID}',[PelajarController::class,'pdf'])->name('adaptive.pdf');
        Route::get('/pelajari-video/{subjectID}',[PelajarController::class,'video_link'])->name('adaptive.video_link');
        Route::get('/pelajari-interpreter/{subjectID}',[PelajarController::class,'interpreter'])->name('adaptive.interpreter');
        Route::get('/modul-sebelumnya/{subjectID}',[PelajarController::class,'modul_sebelumnya'])->name('adaptive.modulsebelumnya');
        Route::get('/modul-pertama/{subjectID}',[PelajarController::class,'modul_pertama'])->name('adaptive.modulpertama');
        Route::get('/setting-akun',[PelajarController::class,'setting_akun'])->name('adaptive.settingAkun');
        Route::put('/edit-akun/{userID}',[PelajarController::class,'edit_akun'])->name('adaptive.editAkun');
        Route::put('/start-timer/{subjectID}',[PelajarController::class,'startTimer'])->name('adaptive.starttimerpdf');
        Route::put('/start-timer-video/{subjectID}',[PelajarController::class,'videostarttimer'])->name('adaptive.starttimervideo');
        Route::put('/start-timer-interpreter/{subjectID}',[PelajarController::class,'interpreterstarttimer'])->name('adaptive.starttimerinterpreter');     
        Route::put('/end-timer/{subjectID}',[PelajarController::class,'endTimer'])->name('adaptive.endtimerpdf');
        Route::put('/end-timer-video/{subjectID}',[PelajarController::class,'videoendtimer'])->name('adaptive.endtimervideo');
        Route::put('/end-timer-interpreter/{subjectID}',[PelajarController::class,'interpreterendtimer'])->name('adaptive.endtimerinterpreter');
        Route::put('/taken-timer/{subjectID}',[PelajarController::class,'takenTimer'])->name('adaptive.endtimerpdf');
        Route::put('/taken-timer-video/{subjectID}',[PelajarController::class,'videotakentimer'])->name('adaptive.takentimervideo');
        Route::put('/taken-timer-interpreter/{subjectID}',[PelajarController::class,'interpretertakentimer'])->name('adaptive.takentimerinterpreter');
        Route::get('/hasil-test-saya', function () {
            $user = Auth::user();
            $HTPs = HasilTestPelajar::where('user_id', $user->id)->paginate(10);
                return view('dashboard.hasil_test_saya', [
                'title' => 'Hasil Test Saya',
                'HTPs' => $HTPs
           ]);
       });
});

 });