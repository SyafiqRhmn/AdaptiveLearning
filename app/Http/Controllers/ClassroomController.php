<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\PreTest;
use App\Models\PostTest;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateClassroomRequest;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('dd');
        return view('dashboard.guru.classroom.index', [
            'title' => 'Kelas',
            'classrooms' => Classroom::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.classroom.create', ['title' => 'Buat classroom baru']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new User model instance with the validated data
        $classroom = new Classroom([
            'name' => $validatedData['name'],
        ]);

        // Save the user to the database
        $classroom->save();

        // Redirect the user to the home page with a success message
        return redirect('/classroom')->with('success', 'Classroom created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        return view('dashboard.guru.classroom.edit', ['title' => 'Edit classroom baru', 'classroom' => $classroom]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $classroom->name = $validatedData['name'];
        $classroom->save();
        return redirect()->route('classroom.index')->with('success', 'Classroom berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return redirect()->route('classroom.index')->with('success', 'Classroom berhasil dihapus.');
    }
}
