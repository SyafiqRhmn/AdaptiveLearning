<?php

namespace App\Http\Controllers;
use App\Models\HasilGayaBelajar;
use App\Models\User;

class HasilGayaBelajarController extends Controller
{
    public function index()
    {
        // Ambil data hasil gaya belajar beserta data user yang terkait
        $hasilGayaBelajar = HasilGayaBelajar::with('user:id,name,nim') // Ambil nama dan nim dari tabel users
            ->get(['gaya_belajar', 'user_id']); // Ambil gaya_belajar dan user_id dari tabel hasil_gaya_belajar

        return view('dashboard.nonpersonalisasi.gaya_belajar', [
            'title' => 'Hasil Gaya Pelajar',
            'hasilGayaBelajar' => $hasilGayaBelajar
        ]);
    }
}