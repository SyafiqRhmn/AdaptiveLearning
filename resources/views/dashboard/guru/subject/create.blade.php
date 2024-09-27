@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">
            <form method="POST" action="{{ route('subject.store') }}" class="p-1" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Nama Subject</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Subject Name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="classroom">Pilih Classroom</label>
                    <select name="classroom" class="form-control @error('classroom') is-invalid @enderror" id="classroom">
                        <option disabled selected>Pilih classroom</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                    @error('classroom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="subject" class="form-label">Modul</label>
                    <input type="file" name="subject" class="form-control @error('subject') is-invalid @enderror" id="subject" subject="subject" placeholder="Enter subject" value="{{ old('subject') }}">
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="deskripsi">Keterangan:</label>
                    <textarea class="{{ $errors->has('deskripsi') ? ' is-invalid' : '' }}" name="deskripsi" id="editor1" required>{{ old('deskripsi') }}</textarea>
                    @if ($errors->has('deskripsi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('deskripsi') }}
                        </div>
                    @endif
                </div>
                <div class="form-group mb-3">
                    <label for="video_link">link video youtube:</label>
                    <textarea class="{{ $errors->has('video_link') ? ' is-invalid' : '' }}" name="video_link" id="editor1" required>{{ old('video_link') }}</textarea>
                    @if ($errors->has('video_link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('video_link') }}
                        </div>
                    @endif
                </div>
                <div>
                    <a href="{{ route('subject.index') }}" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a>
                    <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

<script>
    CKEDITOR.replace('editor1');
</script>

@endsection