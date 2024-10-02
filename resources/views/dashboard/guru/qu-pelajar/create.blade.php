@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">
            <form method="POST" action="{{ route('qu-pelajar.store') }}" class="p-1">
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Pertanyaan</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="pertanyaan" name="pertanyaan" placeholder="Enter Question" value="{{ old('pertanyaan') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="kategori">Pilih Gaya Belajar Kuisioner:</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="">-- Pilih Gaya Belajar --</option>
                        <option value="V">Visual</option>
                        <option value="A">Auditori</option>
                        <option value="K">Kinestetik</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="id_user">ID User (opsional):</label>
                    <input disabled type="text" class="form-control" id="id_user" name="id_user" placeholder="Enter User ID" value="{{ old('id_user') }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


</div>
</div>

@endsection