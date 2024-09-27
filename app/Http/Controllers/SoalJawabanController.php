<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\CourseTest;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class SoalJawabanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.soal_jawaban.index', [
            'title' => 'Soal jawaban',
            'soalJawabans' => Question::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.soal_jawaban.create', [
            'title' => 'Create new question',
            'subjects' => Subject::all(),
            'preTests' => PreTest::all(),
            'postTests' => PostTest::all(),
            'courseTests' => CourseTest::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi question
        $request->validate([
            'question' => 'required',
            'subject_id' => 'required',
            'testable_type' => 'required',
            'testable_id' => 'required',
        ]);
        $question = new Question;
        $question->question = $request->question;
        $question->subject_id = $request->subject_id;
        $question->testable_type = $request->testable_type;
        $question->testable_id = $request->testable_id;

        // validasi jawaban 1
        if ($request->jawaban1 !== null) {
            $request->validate([
                'benar_salah1' => 'required'
            ]);
            $jawaban1 = new Answer;
            $jawaban1->jawaban = $request->jawaban1;
            $jawaban1->benar_salah = $request->benar_salah1;
        }
        // validasi jawaban 2
        if ($request->jawaban2 !== null) {
            $request->validate([
                'benar_salah2' => 'required'
            ]);
            $jawaban2 = new Answer;
            $jawaban2->jawaban = $request->jawaban2;
            $jawaban2->benar_salah = $request->benar_salah2;
        }
        // validasi jawaban 3
        if ($request->jawaban3 !== null) {
            $request->validate([
                'benar_salah3' => 'required'
            ]);
            $jawaban3 = new Answer;
            $jawaban3->jawaban = $request->jawaban3;
            $jawaban3->benar_salah = $request->benar_salah3;
        }
        // validasi jawaban 4
        if ($request->jawaban4 !== null) {
            $request->validate([
                'benar_salah4' => 'required'
            ]);
            $jawaban4 = new Answer;
            $jawaban4->jawaban = $request->jawaban4;
            $jawaban4->benar_salah = $request->benar_salah4;
        }

        // menyimpan semua data
        $question->save();
        $latestQuestion = Question::latest()->first();
        
        if (isset($jawaban1)) {
            $jawaban1->question_id = $latestQuestion->id;
            $jawaban1->save();
        }
        if (isset($jawaban2)) {
            $jawaban2->question_id = $latestQuestion->id;
            $jawaban2->save();
        }
        if (isset($jawaban3)) {
            $jawaban3->question_id = $latestQuestion->id;
            $jawaban3->save();
        }
        if (isset($jawaban4)) {
            $jawaban4->question_id = $latestQuestion->id;
            $jawaban4->save();
        }

        return redirect()->route('soal-jawaban.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd(Question::where('id', $id)->first());
        return view('dashboard.guru.soal_jawaban.show', [
            'title' => 'Soal',
            'soalJawaban' => Question::where('id', $id)->first()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('dashboard.guru.soal_jawaban.edit', [
            'title' => 'Edit soal',
            'soalJawaban' => Question::where('id', $id)->first(),
            'preTests' => PreTest::all(),
            'postTests' => PostTest::all(),
            'courseTests' => CourseTest::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request);
        // validasi question
        $request->validate([
            'question' => 'required',
            'testable_type' => 'required',
            'testable_id' => 'required',
        ]);
        $question = Question::find($id);
        $question->question = $request->question;
        $question->testable_type = $request->testable_type;
        $question->testable_id = $request->testable_id;

        // validasi jawaban 1
        if ($request->jawaban1 !== null) {
            $request->validate([
                'benar_salah1' => 'required',
                'jawaban1' => 'required',
            ]);
            if ($request->jawaban_id1 === null) {
                $jawaban1 = new Answer;
            } else {
                $jawaban1 = Answer::find($request->jawaban_id1);
            }
            $jawaban1->jawaban = $request->jawaban1;
            $jawaban1->benar_salah = $request->benar_salah1;
        }
        // validasi jawaban 2
        if ($request->jawaban2 !== null) {
            $request->validate([
                'benar_salah2' => 'required',
                'jawaban2' => 'required',
            ]);
            if ($request->jawaban_id2 === null) {
                $jawaban2 = new Answer;
            } else {
                $jawaban2 = Answer::find($request->jawaban_id2);
            }
            $jawaban2->jawaban = $request->jawaban2;
            $jawaban2->benar_salah = $request->benar_salah2;
        }
        // validasi jawaban 3
        if ($request->jawaban3 !== null) {
            $request->validate([
                'benar_salah3' => 'required',
                'jawaban3' => 'required',
            ]);
            if ($request->jawaban_id3 === null) {
                $jawaban3 = new Answer;
            } else {
                $jawaban3 = Answer::find($request->jawaban_id3);
            }
            $jawaban3->jawaban = $request->jawaban3;
            $jawaban3->benar_salah = $request->benar_salah3;
        }
        // validasi jawaban 4
        if ($request->jawaban4 !== null) {
            $request->validate([
                'benar_salah4' => 'required',
                'jawaban4' => 'required',
            ]);
            if ($request->jawaban_id4 === null) {
                $jawaban4 = new Answer;
            } else {
                $jawaban4 = Answer::find($request->jawaban_id4);
            }
            $jawaban4->jawaban = $request->jawaban4;
            $jawaban4->benar_salah = $request->benar_salah4;
        }

        // menyimpan semua data
        $question->save();
        
        if (isset($jawaban1)) {
            $jawaban1->question_id = $id;
            $jawaban1->save();
        }
        if (isset($jawaban2)) {
            $jawaban2->question_id = $id;
            $jawaban2->save();
        }
        if (isset($jawaban3)) {
            $jawaban3->question_id = $id;
            $jawaban3->save();
        }
        if (isset($jawaban4)) {
            $jawaban4->question_id = $id;
            $jawaban4->save();
        }

        return redirect()->route('soal-jawaban.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::find($id);
        $question->delete();
        return redirect()->route('soal-jawaban.index')->with('success', 'Data berhasil dihapus.');
    }
}
