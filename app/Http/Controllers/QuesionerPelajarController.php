<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\Question;
use App\Models\Classroom;
use App\Models\Kuisioner;
use App\Models\QuesionerPelajar;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuesionerRequest;
use App\Http\Requests\UpdateQuesionerRequest;

class QuesionerPelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $no=1;
        $quesioners = QuesionerPelajar::all();
        // dd('dd');
        return view('dashboard.guru.qu-pelajar.index', [
            'title' => 'Quesioner Pelajar',
            'quesioner' => $quesioners,
            'no'    => $no
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.qu-pelajar.create', ['title' => 'Buat quesioner baru']);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'kategori' => 'required|string|max:1',
        ]);

        // Create a new User model instance with the validated data
        $quesioner = new QuesionerPelajar([
            'pertanyaan' => $validatedData['pertanyaan'],
            'kriteria' => $validatedData['kategori'],
        ]);

        // Save the user to the database
        $quesioner->save();

        // Redirect the user to the home page with a success message
        return redirect('/quesioner/qu-pelajar')->with('success', 'Quesioner created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuesionerPelajar $qu_pelajar)
    {
        // Tidak perlu memanggil QuesionerPelajar::find() lagi
        return view('dashboard.guru.qu-pelajar.edit', [
        'title' => 'Edit Pertanyaan baru', 
        'quesioner' => $qu_pelajar
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuesionerPelajar $qu_pelajar)
    {
        $validatedData = $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'kriteria' => 'required|string|max:1',
        ]);
        $qu_pelajar->pertanyaan = $validatedData['pertanyaan'];
        $qu_pelajar->kriteria = $validatedData['kriteria'];
        $qu_pelajar->save();
        return redirect()->route('qu-pelajar.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuesionerPelajar $qu_pelajar)
    {
        $qu_pelajar->delete();
        return redirect()->route('qu-pelajar.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }

}

