<?php

namespace App\Http\Controllers;

use App\Models\PreTest;
use App\Models\Question;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\StorePreTestRequest;
use App\Http\Requests\UpdatePreTestRequest;
use App\Models\Answer;

class PreTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.pre_test.index', [
            'title' => 'Pre Test',
            'PreTests' => PreTest::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.pre_test.create', [
            'title' => 'Tambah data pre test',
            'classrooms' => Classroom::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $PreTest = new PreTest;
        $request->validate([
            'name' => 'required|max:255',
            'classroom_id' => 'required',
        ]);
        $PreTest->name = $request->input('name');
        $PreTest->classroom_id = $request->input('classroom_id');
        $PreTest->save();

        return redirect()->route('pre-test.index')->with('success', 'Pre test baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PreTest $preTest)
    {
        return view('dashboard.guru.pre_test.show', [
            'title' => 'Pre test '.$preTest->name,
            'preTest' => $preTest
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PreTest $preTest)
    {
        return view('dashboard.guru.pre_test.edit', [
            'title' => 'Edit '.$preTest->name,
            'preTest' => $preTest,
            'classrooms' => Classroom::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PreTest $preTest)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required',
        ]);
        $preTest->name = $validatedData['name'];
        $preTest->classroom_id = $request->input('classroom_id');
        $preTest->save();
        return redirect()->route('pre-test.index')->with('success', 'Pre test berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PreTest $preTest)
    {
        $preTest->delete();
        return redirect()->route('pre-test.index')->with('success', 'Pre Test berhasil dihapus.');
    }
}
