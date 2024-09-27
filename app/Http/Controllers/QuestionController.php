<?php

namespace App\Http\Controllers;

use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\CourseTest;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.question.index', [
            'title' => 'Soal soal',
            'questions' => Question::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.question.create', [
            'title' => 'Tambah soal',
            'preTests' => PreTest::all(),
            'postTests' => PostTest::all(),
            'courseTest'=> CourseTest::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'testable_type' => 'required',
            'testable_id' => 'required',
        ]);
        $question = new Question;
        $question->question = $request->question;
        $question->testable_type = $request->testable_type;
        $question->testable_id = $request->testable_id;
        $question->save();
        return redirect()->route('question.index')->with('success', 'Berhasil menambahkan soal.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('dashboard.guru.question.show', [
            'title' => 'Soal',
            'question' => $question
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        return view('dashboard.guru.question.edit', [
            'title' => 'Edit soal',
            'question' => $question,
            'preTests' => PreTest::all(),
            'postTests' => PostTest::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required',
            'testable_type' => 'required',
            'testable_id' => 'required',
        ]);
        $question = new Question;
        $question->question = $request->question;
        $question->testable_type = $request->testable_type;
        $question->testable_id = $request->testable_id;
        $question->save();
        return redirect()->route('question.index')->with('success', 'Berhasil merubah data.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('question.index')->with('success', 'Berhasil menghapus data.');
    }
}
