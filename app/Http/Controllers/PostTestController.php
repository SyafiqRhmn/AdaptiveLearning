<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\PostTest;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostTestRequest;
use App\Http\Requests\UpdatePostTestRequest;
use App\Models\Classroom;

class PostTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.post_test.index', [
            'title' => 'Post Test',
            'postTests' => PostTest::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.post_test.create', [
            'title' => 'Tambah data post test',
            'classrooms' => Classroom::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $postTest = new PostTest;
        $request->validate([
            'name' => 'required|max:255',
            'classroom_id' => 'required',
        ]);
        $postTest->name = $request->input('name');
        $postTest->classroom_id = $request->input('classroom_id');
        $postTest->save();

        return redirect()->route('post-test.index')->with('success', 'Post test baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PostTest $postTest)
    {
        return view('dashboard.guru.post_test.show', [
            'title' => 'Post test '.$postTest->name,
            'postTest' => $postTest
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostTest $postTest)
    {
        return view('dashboard.guru.post_test.edit', [
            'title' => 'Edit '.$postTest->name,
            'postTest' => $postTest,
            'classrooms' => Classroom::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostTest $postTest)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'classroom_id' => 'required',
        ]);
        $postTest->name = $validatedData['name'];
        $postTest->classroom_id = $request->input('classroom_id');
        $postTest->save();
        return redirect()->route('post-test.index')->with('success', 'Post test berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostTest $postTest)
    {
        $postTest->delete();
        return redirect()->route('post-test.index')->with('success', 'Post Test berhasil dihapus.');
    }
}
