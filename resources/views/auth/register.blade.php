@extends('layouts.main')
@section('container')


<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h2 class="text-center mb-4">Register</h2>
                    <form method="POST" action="/register">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" placeholder="Enter name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nim" class="form-label">NIM/NIK/ID</label>
                            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" id="nim" value="{{ old('nim') }}" placeholder="Enter id">
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" placeholder="Enter email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="program_studi" class="form-label">Program Studi</label>
                            <input type="text" name="program_studi" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" value="{{ old('program_studi') }}" placeholder="Enter program_studi">
                            @error('program_studi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}" placeholder="Password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <select name="kelas" class="form-select @error('kelas') is-invalid @enderror" id="kelas">
                                <option disabled {{ old('kelas') ? '' : 'selected' }}>Pilih Kelas</option>
                                <option value="A" {{ old('kelas') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('kelas') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('kelas') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('kelas') == 'D' ? 'selected' : '' }}>D</option>
                                <option value="E" {{ old('kelas') == 'E' ? 'selected' : '' }}>E</option>
                                <option value="F" {{ old('kelas') == 'C' ? 'selected' : '' }}>F</option>
                                <option value="G" {{ old('kelas') == 'G' ? 'selected' : '' }}>G</option>
                                <option value="H" {{ old('kelas') == 'H' ? 'selected' : '' }}>H</option>
                            </select>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror                            
                        </div>
                        <div class="form-group mb-3" hidden>
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" id="role">
                                <option disabled {{ old('role') ? 'selected' : '' }}>Pilih Role</option>
                                <option value="pelajar" {{ old('role') == 'pelajar' ? '' : 'selected' }}>Pelajar</option>
                                <!-- <option value="pelajar" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>                             -->
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror                            
                        </div>
                        <div class="form-group mb-3" hidden>
                            <label for="tipe" class="form-label">Tipe</label>
                            <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" id="tipe">
                                <option disabled {{ old('tipe') ? '' : 'selected' }}>Pilih Tipe</option>
                                <option value="reguler" {{ old('tipe') == 'reguler' ? '' : 'selected' }}>Reguler</option>
                                <!-- <option value="adaptive" {{ old('tipe') == 'adaptive' ? 'selected' : '' }}>Adaptive</option> -->
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
    </div>
</div>


@endsection