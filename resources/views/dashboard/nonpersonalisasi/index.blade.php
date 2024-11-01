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
                          @if ($classrooms->isEmpty()) 
                            <p class="card-text">Anda belum mengikuti kelas apapun. </p> 
                            <a href="{{ route('reguler.all-class') }}" class="btn btn-primary btn-sm">Silahkan ikuti kelas di sini</a>
                          @else
                              @foreach ($classrooms as $classroom)
                                  <p class="card-text">Anda adalah anggota dari kelas-kelas berikut:</p>
                                  <a href="{{ route('reguler.my-class.classroom', [$classroom->classroom_id]) }}" class="list-group-item btn btn-outline-dark text-left my-1 border border-dark">
                                      {{ $loop->iteration }}.&nbsp;{{ $classroom->classroom->name }}
                                  </a>
                              @endforeach
                          @endif
                      </ul>                  
                  </div>
              </div>              
          </div>
      </div>
  </div>



  </div>
</div>

@endsection