@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <a href="{{ route('soal-jawaban.create') }}" class="mt-4 btn btn-primary">Tambah Soal</a>
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
                        <th scope="col">Jawaban</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($soalJawabans as $soalJawaban)    
                        <tr>
                          <th>{{ $loop->iteration + ($soalJawabans->perPage() * ($soalJawabans->currentPage() - 1)) }}</th>
                          <td>
                            @php
                                $quest = Str::limit($soalJawaban->question, rand(20,60), '. . .');
                            @endphp
                            {!! $quest !!}
                          </td>
                          <td>
                            @php
                                $correctAnswer = $soalJawaban->answers->where('benar_salah', 'benar')->first();
                                $quest = $correctAnswer ? Str::limit($correctAnswer->jawaban, rand(20, 60), '. . .') : '';
                            @endphp
                            {!! $quest !!}
                        </td>                        
                         
                          <td>
                            @if ($soalJawaban->testable_type === 'pretest') 
                              {{ $soalJawaban->testable_type }} / {{ App\Models\PreTest::where('id', $soalJawaban->testable_id)->value('name') ?? 'N/A' }} /
                            @elseif ($soalJawaban->testable_type === 'posttest')
                              {{ $soalJawaban->testable_type }} / {{ App\Models\PostTest::where('id', $soalJawaban->testable_id)->value('name') ?? 'N/A' }} /
                            @elseif ($soalJawaban->testable_type === 'coursetest')
                              {{ $soalJawaban->testable_type }} / {{ App\Models\CourseTest::where('id', $soalJawaban->testable_id)->value('name') ?? 'N/A' }} /
                            @endif
                              subject {{ optional($soalJawaban->subject)->name }}
                          </td>
                          <td>
                            <a title="lihat data" href="{{ route('soal-jawaban.show', $soalJawaban->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></a>
                            <a title="edit data" href="{{ route('soal-jawaban.edit', $soalJawaban->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('soal-jawaban.destroy', $soalJawaban->id) }}" class="d-inline" method="POST">
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
                    {{ $soalJawabans->links() }}
                </div>
            </div>
        </div>
    </div>


  </div>
</div>

@endsection