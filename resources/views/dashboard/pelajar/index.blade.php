@if (session('title'))    
    @php
        $title = session('title');
        $classrooms = session('classrooms');
        $myClassrooms = session('myClassrooms');
    @endphp
@endif
@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">My Classroom</h5>
                  <p class="card-text">Anda adalah anggota dari kelas-kelas berikut:</p>
                  <ul class="list-group">
                    @foreach ($classrooms as $classroom)
                    <a href="{{ route('adaptive.my-class.classroom', [$classroom->classroom_id]) }}" class="list-group-item btn btn-outline-dark text-left my-1 border border-dark">
                        {{ $loop->iteration }}.&nbsp;
                        {{ $classroom->classroom->name }}
                    </a>
                    @endforeach
                  </ul>                  
                </div>
              </div>              
            </div>
        </div>
    </div>


  </div>
</div>

@endsection