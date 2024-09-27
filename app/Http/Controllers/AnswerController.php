<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.answer.index',[
            'title' => 'Semua jawaban',
            'answers' => Answer::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.answer.create', [
            'title' => 'Buat jawaban personal',
            'questions' => Question::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'jawaban' => 'required',
            'question_id' => 'required',
            'benar_salah' => 'required'
        ]);
        $answer = new Answer;
        $answer->jawaban = $data['jawaban'];
        $answer->question_id = $data['question_id'];
        $answer->benar_salah = $data['benar_salah'];
        $answer->save();

        return redirect()->route('answer.index')->with('success', 'Berhasil menambahkan jawaban');
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        return view('dashboard.guru.answer.show', [
            'title' => 'jawaban '.$answer->id,
            'answer' => $answer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Answer $answer)
    {
        return view('dashboard.guru.answer.edit', [
            'title' => 'Edit jawaban personal',
            'questions' => Question::all(),
            'answer' => $answer
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $data = $request->validate([
            'jawaban' => 'required',
            'question_id' => 'required',
            'benar_salah' => 'required'
        ]);
    
        $answer->jawaban = $data['jawaban'];
        $answer->question_id = $data['question_id'];
        $answer->benar_salah = $data['benar_salah'];
        $answer->save();
    
        return redirect()->route('answer.index')->with('success', 'Berhasil memperbarui jawaban');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();
        return redirect()->route('answer.index')->with('success', 'Berhasil menghapus data.');
    }
}
