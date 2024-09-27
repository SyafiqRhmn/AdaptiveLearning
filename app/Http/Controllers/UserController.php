<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.user.index', ['title' => 'Data User', 'users' => User::paginate(10)]);
    }
    
    public function pelajaradaptive()
    {
        return view('dashboard.user.index', ['title' => 'Data User Pelajar Adaptive', 'users' => User::where('tipe', 'adaptive')->paginate(10)]);
    }
    public function pelajarreguler()
    {
        return view('dashboard.nonpersonalisasi.index', ['title' => 'Data User Pelajar Reguler', 'users' => User::where('tipe', 'reguler')->paginate(10)]);
    }
    
    public function guru()
    {
        return view('dashboard.user.index', ['title' => 'Data User Guru', 'users' => User::where('role', 'guru')->paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd('d');
        return view('dashboard.user.create',[
            'title' => 'Membuat data baru'
        ]);

        // Redirect the user to the home page with a success message
        return redirect('/user')->with('success', 'User created successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|numeric',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:pelajar,guru,admin',
        ]);

        // Proses penyimpanan data ke database
        User::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // Redirect atau berikan respons yang sesuai
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    } catch (\Exception $e) {
        // Tampilkan pesan kesalahan
        dd($e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('dashboard.user.edit',[
            'title' => 'mengedit data',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Temukan user yang akan diperbarui
        // $user = User::findOrFail($id);

        // Validasi input
        $request->validate([
            'name' => 'required',
            'nim' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,pelajar,guru',
            'tipe' => 'required|in:reguler,adaptive',
        ]);


        // Update data user
        $user->name = $request->name;
        $user->nim = $request->nim;
        $user->email = $request->email;
        $user->role = $request->role;
        $tipe->tipe = $request->tipe;

        // Periksa apakah password dikirim untuk diperbarui
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman yang sesuai atau tampilkan pesan sukses
        return redirect('/user')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $user = User::find($id);
        $email = $user->email;
        $username = substr($email, 0, strpos($email, '@'));

        $user->password = Hash::make($username);
        $user->save();

        return redirect()->route('user.index')->with('success', 'Password user berhasil diperbarui menjadi: ' . $username);
    }
}
