@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
    <form action="/hasil-test-pelajar" method="GET">
    <input type="text" name="q" value="{{ $query }}" placeholder="Cari...">
    <div class="form-group form-inline ml-3">
        <label for="Kelas" class="mr-2">Kelas:</label>
        <select class="form-control" id="Kelas" name="Kelas">
            <option value="" {{ request('Kelas') == '' ? 'selected' : '' }}>Semua</option>
            <option value="A" {{ request('Kelas') == 'A' ? 'selected' : '' }}>A</option>
            <option value="B" {{ request('Kelas') == 'B' ? 'selected' : '' }}>B</option>
            <option value="C" {{ request('Kelas') == 'C' ? 'selected' : '' }}>C</option>
            <option value="D" {{ request('Kelas') == 'D' ? 'selected' : '' }}>D</option>
            <option value="E" {{ request('Kelas') == 'E' ? 'selected' : '' }}>E</option>
            <option value="F" {{ request('Kelas') == 'F' ? 'selected' : '' }}>F</option>
          </select>
    </div>
    <div class="form-group form-inline ml-3">
        <label for="prodi" class="mr-2">prodi:</label>
        <select class="form-control" id="prodi" name="prodi">
            <option value="" {{ request('prodi') == '' ? 'selected' : '' }}>Semua</option>
            <option value="sistem informasi" {{ request('prodi') == 'sistem informasi' ? 'selected' : '' }}>sistem informasi</option>
            <option value="teknik informatika" {{ request('prodi') == 'teknik informatika' ? 'selected' : '' }}>teknik informatika</option>
          </select>
    </div>
    <div class="form-group form-inline ml-3">
        <label for="Modul" class="mr-2">Modul:</label>
        <select class="form-control" id="Modul" name="Modul">
            <option value="" {{ request('Modul') == '' ? 'selected' : '' }}>Semua</option>
            <option value="introduction to algorithm" {{ request('Modul') == 'introduction to algorithm' ? 'selected' : '' }}>introduction to algorithm</option>
            <option value="expression" {{ request('Modul') == 'expression' ? 'selected' : '' }}>expression</option>
            <option value="condition" {{ request('Modul') == 'condition' ? 'selected' : '' }}>condition</option>
            <option value="function" {{ request('Modul') == 'function' ? 'selected' : '' }}>function</option>
            <option value="iteration" {{ request('Modul') == 'iteration' ? 'selected' : '' }}>iteration</option>
            <option value="string" {{ request('Modul') == 'string' ? 'selected' : '' }}>string</option>
            <option value="list" {{ request('Modul') == 'list' ? 'selected' : '' }}>list</option>
            <option value="tuple" {{ request('Modul') == 'tuple' ? 'selected' : '' }}>tuple</option>

        </select>
    </div>
    <button type="submit">Cari</button>  
  </form>
      
        <div class="row">
            <div class="col">
              {{-- <a href="{{ route('HTP.create') }}" class="mt-4 btn btn-primary">Tambah HTP</a> --}}
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
                <table class="table table-striped table-hover my-4 mt-2">
                    <thead class="bg-dark text-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIM - Nama Pelajar</th>
                        <th scope="col">Jenis Test - Classroom</th>
                        <th scope="col">Nama Test</th>
                        <th scope="col">Skor</th>
                        {{-- <th scope="col">action</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($HTPs as $HTP)    
                        <tr>
                          <th>{{ $loop->iteration + ($HTPs->perPage() * ($HTPs->currentPage() - 1)) }}</th>
                          <td>{{ $HTP->user->nim }} - {{ $HTP->user->name }}</td>
                          @php
                            if ($HTP->testable_type === 'pre-test') {
                                $testable = App\Models\PreTest::find($HTP->testable_id);
                              }elseif($HTP->testable_type ==='post-test'){
                                $testable = App\Models\PostTest::find($HTP->testable_id);
                              } else {
                                $testable = App\Models\CourseTest::find($HTP->testable_id);
                              }
                          @endphp
                          <td>{{ $HTP->testable_type }} - {{ $testable->classroom->name }}</td>
                          <td>{{ $testable->name }}</td>
                          <td><b>{{ $HTP->score }}</b></td>
                          {{-- <td> --}}
                            {{-- <a title="lihat data" href="{{ route('HTP.show', $HTP->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a> --}}
                            {{-- <a title="edit data" href="{{ route('HTP.edit', $HTP->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a> --}}
                            {{-- <form action="{{ route('HTP.destroy', $HTP->id) }}" class="d-inline" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="bi bi-trash"></i></button>
                            </form> --}}
                          {{-- </td> --}}
                        </tr>
                      @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $HTPs->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection