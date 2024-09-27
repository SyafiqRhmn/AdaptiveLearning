@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">
            <form method="POST" action="{{ route('course-test.store') }}" class="p-1">
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Nama Course Test</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <select name="classroom_id" class="form-control @error('classroom_id') is-invalid @enderror">
                        <option disabled selected>(pilih classroom)</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id') === $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>
                    @error('classroom_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <select name="subject_id" class="form-control @error('subject_id') is-invalid @enderror">
                        <option disabled selected>(pilih subject)</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') === $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <a href="{{ route('course-test.index') }}" class="btn btn-danger"><i class="bi bi-back"></i>  Back</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


</div>
</div>

@endsection