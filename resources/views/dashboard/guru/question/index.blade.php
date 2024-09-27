@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <a href="{{ route('question.create') }}" class="mt-4 btn btn-primary">Tambah Soal</a>
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
                <table class="table table-striped table-hover my-3">
                    <thead class="bg-dark text-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Soal</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($questions as $question)    
                        <tr>
                          <th>{{ $loop->iteration + ($questions->perPage() * ($questions->currentPage() - 1)) }}</th>
                          <td>
                            @php
                                $quest = Str::limit($question->question, rand(20,60), '. . .');
                            @endphp
                            {!! $quest !!}
                          </td>
                          <td>{{ $question->testable_type }}</td>
                          <td>
                            <a title="lihat data" href="{{ route('question.show', $question->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            <a title="edit data" href="{{ route('question.edit', $question->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('question.destroy', $question->id) }}" class="d-inline" method="POST">
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
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection