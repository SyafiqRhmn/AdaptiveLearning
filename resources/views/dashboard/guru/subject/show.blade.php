@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-10">
            <h3>Subject {{ $subject->name }}</h3>
            <h4>Classroom {{ $subject->classroom->name }}</h4>
            <!-- <div class="pdf-container">
                <iframe width="560" height="315" src="{{ $subject->path }}" frameborder="0" allowfullscreen></iframe>
            </div> -->
            <h5>Modul: <a style="color: blue" href="{{ asset('storage/' . $subject->path) }}" download="{{ $subject->subject }}">{{ $subject->subject }}</a></h5>
            <p>{!! $subject->deskripsi !!}</p>
            <!-- <div class="video-container">
                <iframe width="560" height="315" src="{{ $subject->video_link }}" frameborder="0" allowfullscreen></iframe>
            </div> -->
            <a href="{{ route('subject.index') }}" class="btn btn-primary"><i class="bi bi-back"></i> Back</a>
            <a href="{{ route('subject.edit', $subject->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        </div>
    </div>
</div>


</div>
</div>

<script>
    CKEDITOR.replace('editor1');
</script>

@endsection