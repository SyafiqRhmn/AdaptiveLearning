@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
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