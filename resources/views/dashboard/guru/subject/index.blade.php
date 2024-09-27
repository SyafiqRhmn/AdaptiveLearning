@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <a href="{{ route('subject.create') }}" class="mt-4 btn btn-primary">Tambah modul</a>
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
                <table class="table table-striped table-hover my-4 mt-2">
                    <thead class="bg-dark text-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Modul</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">video_link</th>
                        <th scope="col">Classroom</th>
                        <th scope="col">action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($subjects as $subject)    
                        <tr>
                          <th>{{ $loop->iteration + ($subjects->perPage() * ($subjects->currentPage() - 1)) }}</th>
                          <td>{{ $subject->name }}</td>
                          <td><a style="color: blue" href="{{ asset('storage/' . $subject->path) }}" download="{{ $subject->subject }}">{{ $subject->subject }}</a></td>
                          <td>
                            @php
                                $desc = Str::limit($subject->deskripsi, rand(20,60), '. . .');
                            @endphp
                            {!! $desc !!}
                          </td>
                          <td>{{$subject->video_link}}</td>
                          <td>{{ $subject->classroom->name }}</td>
                          <td>
                            <a title="lihat data" href="{{ route('subject.show', $subject->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            <a title="edit data" href="{{ route('subject.edit', $subject->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('subject.destroy', $subject->id) }}" class="d-inline" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="bi bi-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $subjects->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection