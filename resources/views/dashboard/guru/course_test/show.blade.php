@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-10">
            <h4>{{ $courseTest->name }}</h4>
            <h5>{{ $courseTest->classroom->name }}</h5>
            <div>
                <a href="{{ route('course-test.index') }}" class="btn btn-primary"><i class="bi bi-back"></i>  Back</a>
                <a href="{{ route('course-test.edit', $courseTest->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i>  Edit</a>
            </div>
        </div>
    </div>
</div>


</div>
</div>

@endsection