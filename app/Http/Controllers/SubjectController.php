<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.guru.subject.index', [
            'title' => 'Subject Modul',
            'subjects' => Subject::paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.guru.subject.create', ['title' => 'Buat subject baru', 'classrooms' => Classroom::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:subjects|max:255',
            'classroom' => 'required',
            'subject' => 'nullable|mimes:pdf,docx,doc,jpg,png,txt,xls,xlsx,csv|file|max:64024',
            'video_link' => 'nullable|string',
            'deskripsi' => 'nullable|string',
        ]);
        
        $subject = new Subject;
        if ($request->hasFile('subject')) {
            $file = $request->file('subject');
            $subject->path = $file->store('moduls'); // Menyimpan file di folder "public/moduls"
            $subject->subject = $file->getClientOriginalName(); // Mendapatkan nama file yang disimpan
        }
    
        $subject->name = $request->input('name');
        $subject->classroom_id = $request->input('classroom');
        $subject->deskripsi = $request->input('deskripsi');
        $subject->video_link = $request->input('video_link');
        $subject->save();

        return redirect()->route('subject.index')->with('success', 'Subject baru telah ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        return view('dashboard.guru.subject.show', [
            'title' => $subject->classroom->name,
            'subject' => $subject
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('dashboard.guru.subject.edit',[
            'title' => 'Edit Subject',
            'classrooms' => Classroom::all(),
            'subject' => $subject
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|unique:subjects,name,' . $subject->id . '|max:255',
            'classroom' => 'required',
            'subject' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png,txt,xls,xlsx,csv|max:64024',
            'deskripsi' => 'nullable|string', 
            'video_link' => 'nullable|string',
        ]);

        if ($request->hasFile('subject')) {

            if ($subject->subject !== null) {
                // Hapus file subject yang lama (jika ada)
                Storage::delete($subject->path);
            }

            // Simpan file subject yang baru
            $file = $request->file('subject');
            $subject->path = $file->store('moduls');
            $subject->subject = $file->getClientOriginalName();
        }

        $subject->name = $request->input('name');
        $subject->classroom_id = $request->input('classroom');
        $subject->deskripsi = $request->input('deskripsi');
        $subject->video_link = $request -> input('video_link');
        $subject->save();
        return redirect()->route('subject.index')->with('success', 'Subject berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->path !== null) {
            Storage::delete($subject->path);
        }
        $subject->delete();
        return redirect()->route('subject.index')->with('success', 'Subject berhasil dihapus');
    }
}
