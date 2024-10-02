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
use App\Http\Requests\UpdateQuesionerRequest;

class QuesionerGuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('dd');
        return view('dashboard.guru.qu-guru.index', [
            'title' => 'Quesioner Guru',
        ]);
    }

}

