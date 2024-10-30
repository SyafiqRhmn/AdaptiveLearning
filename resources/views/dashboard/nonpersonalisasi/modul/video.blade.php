@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
            <div class="col">
              <div class="card p-4">
              <div>
              <span id="timer">Waktu: 00:00</span>
            </div>
                <h3>{{ $classroom->name }}</h3>
                <iframe width="560" height="315" src="{{ $subject->video_link }}" frameborder="0" allowfullscreen></iframe>
                <a href="{{ route('reguler.my-class.classroom', [$classroom->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;">kembali</a>
                <!-- <a href="{{ route('reguler.subjecttest.do', ['test'=> 'course-test','subjectID' => $subject->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;" onclick="endAndCalculate('{{ $subject->id }}');">Lanjut</a> -->
              </div>              
            </div>
        </div>
    </div>
    <script>
              let timerInterval;
let startTime;
let elapsedSeconds = 0;

function startTimer(taken = 0) {
  elapsedSeconds = taken;
  startTime = new Date();
  timerInterval = setInterval(updateTimer, 1000);
}

function updateTimer() {
  const currentTime = new Date();
  const totalSeconds = elapsedSeconds + Math.floor((currentTime - startTime) / 1000);
  const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
  const seconds = String(totalSeconds % 60).padStart(2, '0');
  document.getElementById('timer').innerText = `Waktu: ${minutes}:${seconds}`;
}

function pauseTimer(subjectId) {
  clearInterval(timerInterval);
  const totalElapsed = elapsedSeconds + Math.floor((new Date() - startTime) / 1000);

  fetch(`{{ url('reguler/end-timer') }}/${subjectId}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-Token": "{{ csrf_token() }}"
    },
    body: JSON.stringify({ taken_time: totalElapsed })
  })
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error("Error:", error));
}

// Mulai dari `taken_time` yang sudah disimpan
document.addEventListener("DOMContentLoaded", function() {
  const subjectId = {{ $subject->id }};
  fetch(`{{ url('reguler/start-timer') }}/${subjectId}`)
    .then(response => response.json())
    .then(data => {
      startTimer(data.taken_time);
    })
    .catch(error => console.error("Error:", error));
});

            </script>

  </div>
</div>

@endsection