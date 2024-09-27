@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <a href="{{ route('answer.create') }}" class="mt-4 btn btn-primary">Tambah Jawaban</a>
              @if (session('success'))
                  <div class="alert alert-success mt-3">
                      {{ session('success') }}
                  </div>
              @endif
                <table class="table table-striped table-hover my-4 mt-2">
                    <thead class="bg-dark text-light">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Jawaban</th>
                        <th scope="col">Status</th>
                        <th scope="col">Soal</th>
                        <th scope="col">action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($answers as $answer)    
                        <tr>
                          <th>{{ $loop->iteration + ($answers->perPage() * ($answers->currentPage() - 1)) }}</th>
                          <td>
                            @php
                                $quest = Str::limit($answer->jawaban, rand(20,50), '. . .');
                            @endphp
                            {!! $quest !!}
                          </td>
                          <td>
                            @if ($answer->benar_salah === 'benar')
                                <p class="text-success">{{ $answer->benar_salah }}</p>
                            @else    
                                <p class="text-danger">{{ $answer->benar_salah }}</p>
                            @endif
                          </td>
                          <td>
                            @php
                                $quest = Str::limit($answer->question->question, rand(20,50), '. . .');
                            @endphp
                            {!! $quest !!}
                          </td>
                          <td>
                            <a title="lihat data" href="{{ route('answer.show', $answer->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            <a title="edit data" href="{{ route('answer.edit', $answer->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('answer.destroy', $answer->id) }}" class="d-inline" method="POST">
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
                    {{ $answers->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection