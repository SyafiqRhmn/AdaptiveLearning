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
                <a href="{{ route('adaptive.my-class.classroom', [$classroom->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;">kembali</a>
                <a href="{{ route('adaptive.subjecttest.do', ['test'=> 'course-test','subjectID' => $subject->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;" onclick="endAndCalculate('{{ $subject->id }}');">Lanjut</a>
              </div>              
            </div>
        </div>
    </div>
    <script>
              // Fungsi untuk memulai timer
              function startTimer() {
                startTime = new Date();
                timerInterval = setInterval(updateTimer, 1000);  // Update timer setiap detik
              }

              // Fungsi untuk mengupdate timer
              function updateTimer() {
                const currentTime = new Date();
                const elapsedSeconds = Math.floor((currentTime - startTime) / 1000);
                const minutes = Math.floor(elapsedSeconds / 60).toString().padStart(2, '0');
                const seconds = (elapsedSeconds % 60).toString().padStart(2, '0');
                document.getElementById('timer').innerText = `Waktu: ${minutes}:${seconds}`;
              }

              startTimer();  // Panggil fungsi startTimer() untuk memulai timer
              function endTimer(subjectId) {
  console.log(subjectId);
  // Lakukan permintaan PUT untuk memulai timer
  fetch(`{{ url('adaptive/end-timer-video') }}/${subjectId}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-Token": "{{ csrf_token() }}"
    }
  })
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error("Error:", error));
}
function calculateTakenTime(subjectId) {
        // Lakukan permintaan PUT untuk menghitung taken time
        fetch(`{{ route('adaptive.takentimervideo', ['subjectID' => $subject->id]) }}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-Token": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error("Error:", error));
    }
    function endAndCalculate(subjectId) {
        // Panggil endTimer
        endTimer(subjectId);

        // Panggil calculateTakenTime
        calculateTakenTime(subjectId);
    }

            </script>

  </div>
</div>

@endsection