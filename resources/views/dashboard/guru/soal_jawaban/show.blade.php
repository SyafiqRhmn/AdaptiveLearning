@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<div class="container my-5">
    <div class="row">
        <div class="col-10">
            @if ($soalJawaban->testable_type === 'pre-test')
                <p> <b>Jenis Test: Pre-Test</b></p>
                <p> <b>Nama Pre-Test: {{ App\Models\PreTest::where('id', $soalJawaban->testable_id)->first()->name }}</b></p>
            @elseif ($soalJawaban->testable_type === 'post-test')
                <p><b>Jenis Test: Post-Test</b></p>
                <p><b>Nama Post-Test: {{ App\Models\PostTest::where('id', $soalJawaban->testable_id)->first()->name }}</b></p>
                @elseif ($soalJawaban->testable_type === 'course-test')
                <p><b>Jenis Test: Course-Test</b></p>
                <p><b>Nama Course-Test: {{ App\Models\CourseTest::where('id', $soalJawaban->testable_id)->first()->name }}</b></p>
            @endif
            <b>Soal/Pertanyaan:</b>
            <p>{!! $soalJawaban->question !!}</p>
            <b>Jawaban:</b>
            <div class="answers">
                <table class="table border border-sm">
                @foreach ($soalJawaban->answers as $answer)
                <tr>
                    <td>{{ chr($loop->index + 1 + 64) }}.</td>
                    <td>{!! $answer->jawaban !!}
                        @if ($answer->benar_salah === 'benar')
                            <b class="text-success">{{ $answer->benar_salah }}</b>
                        @else    
                            <b class="text-danger">{{ $answer->benar_salah }}</b>
                        @endif
                    </td>
                </tr>
                @endforeach
                </table>
            </div>            
            <div>
                <a href="{{ route('soal-jawaban.index') }}" class="btn btn-primary"><i class="bi bi-back"></i>  Back</a>
                <a href="{{ route('soal-jawaban.edit', $soalJawaban->id) }}" class="btn btn-warning"><i class="bi bi-pencil"></i>  Edit</a>
            </div>
        </div>
    </div>
</div>


</div>
</div>

@endsection