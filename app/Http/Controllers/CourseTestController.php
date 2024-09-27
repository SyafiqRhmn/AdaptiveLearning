<?php

namespace App\Http\Controllers;

use App\Models\CourseTest;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseTestRequest;
use App\Http\Requests\UpdateCourseTestRequest;
use App\Models\Answer;

class CourseTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.course_test.index', [
            'title' => 'Course Test',
            'courseTests' => Coursetest::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.course_test.create', [
            'title' => 'Tambah data course test',
            'classrooms' => Classroom::all(),
            'subjects'=>Subject::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $CourseTest = new CourseTest;
        $request->validate([
            'name' => 'required|max:255',
            'classroom_id' => 'required',
            'subject_id' => 'required',
        ]);
        $CourseTest->name = $request->input('name');
        $CourseTest->classroom_id = $request->input('classroom_id');
        $CourseTest->subject_id = $request->input('subject_id');
        $CourseTest->save();

        return redirect()->route('course-test.index')->with('success', 'course test baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseTest $courseTest)
    {
        return view('dashboard.guru.course_test.show', [
            'title' => 'course test '.$courseTest->name,
            'courseTest' => $courseTest
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseTest $courseTest)
    {
        return view('dashboard.guru.course_test.edit', [
            'title' => 'Edit '.$courseTest->name,
            'courseTest' => $courseTest,
            'classrooms' => Classroom::all(),
            'subjects' => Subject::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseTest $courseTest)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required',
            'subject_id'=>'required',
        ]);
        $courseTest->name = $validatedData['name'];
        $courseTest->classroom_id = $request->input('classroom_id');
        $courseTest->subject_id = $request->input('subject_id');
        $courseTest->save();
        return redirect()->route('course-test.index')->with('success', 'Course test berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseTest $courseTest)
    {
        $courseTest->delete();
        return redirect()->route('course-test.index')->with('success', 'Course Test berhasil dihapus.');
    }
}
