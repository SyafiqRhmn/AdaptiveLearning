@extends('layouts.main')
@section('container')


<div class="container my-5">
    <div class="row">
        <div class="col-6 mx-auto">
            <form method="POST" action="{{ route('user.update', $user->id) }}" class="shadow-lg p-5" style="border-radius: 8">
                @method('PUT')
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="nim" class="form-label">NIM/NIK/ID</label>
                    <input type="number" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" placeholder="Enter id" value="{{ old('nim', $user->nim) }}">
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="program_studi" class="form-label">Program Studi</label>
                    <input type="text" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi" placeholder="Enter program_studi" value="{{ old('program_studi', $user->program_studi) }}">
                    @error('program_studi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select @error('kelas') is-invalid @enderror" id="kelas" name="kelas">
                        <option disabled selected>Pilih Kelas</option>
                        <option value="A" {{ old('kelas', $user->kelas) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('kelas', $user->kelas) == 'B' ? 'selected' : '' }}>B</option>                  
                        <option value="C" {{ old('kelas', $user->kelas) == 'C' ? 'selected' : '' }}>A</option>
                        <option value="D" {{ old('kelas', $user->kelas) == 'D' ? 'selected' : '' }}>B</option>
                    </select>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                </div>
                <div class="form-group mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                        <option disabled selected>Pilih Role</option>
                        <option value="pelajar" {{ old('role', $user->role) == 'pelajar' ? 'selected' : '' }}>Pelajar</option>
                        <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tipe" class="form-label">Tipe</label>
                    <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe">
                        <option disabled selected>Pilih Tipe</option>
                        <option value="reguler" {{ old('tipe', $user->tipe) == 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="adaptive" {{ old('tipe', $user->tipe) == 'tipe' ? 'selected' : '' }}>Adaptive</option>
                    </select>
                    @error('tipe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection