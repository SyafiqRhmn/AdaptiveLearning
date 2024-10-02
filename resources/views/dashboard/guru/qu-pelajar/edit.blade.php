@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">
            <form method="POST" action="{{ route('qu-pelajar.update', $quesioner->id) }}" class="p-1">
                @method('PUT')
                @csrf
                <div class="form-group mb-3">
                    <label for="pertanyaan" class="form-label">Pertanyaan</label>
                    <input type="text" class="form-control @error('pertanyaan') is-invalid @enderror" id="name" name="pertanyaan" placeholder="Enter Question" value="{{ old('pertanyaan', $quesioner->pertanyaan) }}">
                    @error('pertanyaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <label for="kriteria">Pilih Gaya Belajar Kuisioner:</label>
                    <select name="kriteria" id="kriteria" class="form-control">
                        <option value="">-- Pilih Gaya Belajar --</option>
                        <option value="V" {{ $quesioner->kriteria == 'V' ? 'selected' : '' }}>Visual</option>
                        <option value="A" {{ $quesioner->kriteria == 'A' ? 'selected' : '' }}>Auditori</option>
                        <option value="K" {{ $quesioner->kriteria == 'K' ? 'selected' : '' }}>Kinestetik</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-stickies"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

  </div>
</div>

@endsection