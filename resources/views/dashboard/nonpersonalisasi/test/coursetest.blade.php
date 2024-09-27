@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


<form id="myForm" method="POST" action="">
  <input type="hidden" name="title" value="{{ $title }}">
@csrf
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-9">
          <div class="card p-3">

            <input type="hidden" name="result" value="{{ json_encode($result) }}">
            <input type="hidden" name="result_choose" value="{{ json_encode($result_choose) }}">
            
            <input type="hidden" name="jumlah_question" value="{{ $questions->count() }}">
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <p>{!! $question->question !!}</p>
            @foreach ($question->answers as $answer)
              @php
                $alphabet = chr(65 + $loop->index);
              @endphp
              <div class="d-flex align-items-center">
                <input type="radio" class="btn-check" name="answer" id="{{ $answer->id }}" autocomplete="off" value="{{ $answer->id }}" @if (isset($result_choose[$question->id]))
                    {{ $result_choose[$question->id] == $answer->id ? 'checked' : '' }}
                @endif>
                <label class="btn btn-outline-info text-dark text-left flex-grow-1 pb-0 pt-3" style="border-color: grey" for="{{ $answer->id }}">
                  <table><tr><td><p>{{ $alphabet }}. &nbsp;</p></td><td>{!! $answer->jawaban !!}</td></tr></table>
                </label>
              </div>
            @endforeach

            {{-- button next --}}
            @php
                $now = false;
                $do = false;
            @endphp
            @foreach ($questions as $quest)
            @if ($quest->id === $question->id)
                @php
                    $now = true;
                @endphp
            @elseif ($now === true)  
            <button type="submit" name="soal" value="{{ $quest->question }}" onclick="setFormAction('{{ route('reguler.subjecttest.question', ['test' => $jenis_test, 'questID' => $quest->id]) }}')" class="btn px-1 py-2 btn-primary my-2 w-25" style="color: white;">next</button>
              @php
                  $now = false;
                  $do = true;
              @endphp
            @elseif ($questions->count() === $quest->id && $do === false)
                <input type="hidden" name="testID" value="{{ $testID }}">
                <input type="hidden" name="test" value="{{ $jenis_test }}">
                <button type="submit" name="submit" value="{{ $testID }}" onclick="return confirm('Apakah Anda telah selesai mengerjakan?') && setFormAction('{{ route('reguler.subjecttest.submit', ['test' => $jenis_test, 'testID' => $testID->id]) }}')" class="btn btn-primary w-25" style="background-color: #3B71CA; color: white;">Kirim</button>
            @endif
            @endforeach
          </div>
        </div>
        <div class="col-3 card">
          <div class="container-fluid">
            <div class="row pt-4">
          @foreach ($questions as $quest)
            <div class="col-4">
              @if ($result[$quest->id] !== null)
                <button type="submit" name="soal" value="{{ $quest->question }}" onclick="setFormAction('{{ route('reguler.subjecttest.question', ['test' => $jenis_test, 'questID' => $quest->id]) }}')" class="btn px-1 py-2 btn-success my-2 w-100" style="color: white;">{{ $loop->iteration }}</button>
              @elseif ($quest->id === $question->id)
                <button type="submit" name="soal" value="{{ $quest->question }}" onclick="setFormAction('{{ route('reguler.subjecttest.question', ['test' => $jenis_test, 'questID' => $quest->id]) }}')" class="btn px-1 py-2 btn-primary my-2 w-100" style="color: white;">{{ $loop->iteration }}</button>
              @else
                <button type="submit" name="soal" value="{{ $quest->question }}" onclick="setFormAction('{{ route('reguler.subjecttest.question', ['test' => $jenis_test, 'questID' => $quest->id]) }}')" class="btn px-1 py-2 my-2 w-100" style="background-color: #3B71CA; color: white;">{{ $loop->iteration }}</button>
              @endif
            </div>
            @if ($loop->iteration % 3 === 0)
              </div>
              <div class="row pt-2">
                  @endif
                @endforeach
              </div>            
            <div class="row py-4">
              <div class="col">
                <input type="hidden" name="testID" value="{{ $testID }}">
                <button type="submit" name="submit" value="{{ $testID }}" onclick="return confirm('Apakah Anda telah selesai mengerjakan?') && setFormAction('{{ route('reguler.subjecttest.submit', ['test' => $jenis_test, 'testID' => $testID]) }}')" class="btn btn-primary w-100">Kirim</button>
              </div>
            </div>
          </div>
        </div>                     
    </div>
</div>
</form>


  </div>
</div>

@if (isset($alert))
    <script>
      alert("{{ $alert }}");
    </script>
@endif

<script>
  function setFormAction(action) {
    var form = document.getElementById('myForm');
    form.action = action;
  }
</script>

@endsection