<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login_view()
    {
        return view('auth.login', ['title' => 'Login']);
    }
    
    public function register_view()
    {
        return view('auth.register', ['title' => 'Register']);
    }

    public function quis_view()
    {
        return view('quisioner.index', ['title' => 'Quisioner']);
    }
    
    public function login_action(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }
        // tambahkan data ke session sebelum redirect back
        $request->session()->flash('loginError', 'Login gagal, username atau password salah!');
        return redirect('/login');
    }
    
    public function register_action(Request $request)
    {
        // Validasi input dari user
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'nim' => 'required|unique:users|max:255',
            'email' => 'required|unique:users|email|max:255',
            'password' => 'required|min:6|max:255|confirmed',
            'program_studi' => 'required|max:255',
            'kelas'=> 'required|in:A,B,C,D,E,F,G,H',
            'role' => 'required|in:pelajar,guru,admin',
            'tipe' => 'required|in:reguler,adaptive',
        ]);

        // Menambahkan user baru ke database
        $user = new User();
        $user->name = $validatedData['name'];
        $user->nim = $validatedData['nim'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->program_studi = $validatedData['program_studi'];
        $user->kelas = $validatedData['kelas'];
        $user->role = $validatedData['role'];
        $user->tipe = $validatedData['tipe'];
        $user->save();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silahkan login.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
