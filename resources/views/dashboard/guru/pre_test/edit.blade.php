@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-8">
            <form method="POST" action="{{ route('pre-test.update', $preTest->id) }}" class="p-1">
                @method('PUT')
                @csrf
                <div class="form-group mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter name" value="{{ old('name', $preTest->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <select name="classroom_id" class="form-control">
                        <option disabled selected>(pilih classroom)</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ old('classroom_id', $preTest->classroom_id) === $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                        @endforeach
                    </select>                    
                </div>
                <div>
                    <a href="{{ route('pre-test.index') }}" class="btn btn-danger"><i class="bi bi-back"></i>  Cancel</a>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-stickies"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

  </div>
</div>

@endsection