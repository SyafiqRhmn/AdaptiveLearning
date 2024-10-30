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
                <iframe src="{{ $filePath }}" width="100%" height="600"></iframe>
                <!-- <a href="{{ route('reguler.my-class.classroom', [$classroom->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;">kembali</a> -->
                <a href="#" class="btn btn-primary mt-4" style="max-width: 110px;" onclick="handleBackButton('{{ $subject->id }}');">Kembali</a>

                <!-- <a href="{{ route('reguler.subjecttest.do', ['test'=> 'course-test','subjectID' => $subject-> id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;" onclick="endAndCalculate('{{ $subject->id }}');">Lanjut</a>            -->
            </div>
        </div>
    </div>
    <script>
       let timerInterval;
let startTime;
let elapsedSeconds = 0;

function startTimer(taken = 0) {
    elapsedSeconds = taken; // Memulai dari nilai taken_time atau 0
    startTime = new Date();
    timerInterval = setInterval(updateTimer, 1000); // Update timer setiap detik
}

function updateTimer() {
    const currentTime = new Date();
    const totalSeconds = elapsedSeconds + Math.floor((currentTime - startTime) / 1000);
    const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
    const seconds = String(totalSeconds % 60).padStart(2, '0');
    document.getElementById('timer').innerText = `Waktu: ${minutes}:${seconds}`;
}

// Memulai timer saat halaman dimuat
document.addEventListener("DOMContentLoaded", function() {
    const subjectId = {{ $subject->id }};
    fetch(`{{ url('reguler/start-timer') }}/${subjectId}`)
        .then(response => response.json())
        .then(data => {
            const initialTime = data.taken_time || 0; // Jika null, mulai dari 0
            startTimer(initialTime);
        })
        .catch(error => {
            console.error("Error:", error);
            startTimer(0); // Jika ada error, mulai dari 0
        });
});

function pauseTimer(subjectId) {
    console.log("Pausing timer for subject ID:", subjectId); // Menambahkan log
    clearInterval(timerInterval);
    const totalElapsed = elapsedSeconds + Math.floor((new Date() - startTime) / 1000);
    
    console.log("Total elapsed time:", totalElapsed); // Menambahkan log

    fetch(`{{ url('reguler/end-timer') }}/${subjectId}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-Token": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ taken_time: totalElapsed })
    })
    .then(response => {
        console.log("Response from server:", response); // Menambahkan log
        return response.json();
    })
    .then(data => {
        console.log("Timer paused and time saved:", data);
    })
    .catch(error => {
        console.error("Error:", error);
    });
}


// Fungsi untuk menangani klik "Kembali"
function handleBackButton(subjectId) {
    pauseTimer(subjectId); // Hentikan dan simpan waktu
    // Delay 1 detik sebelum kembali
    setTimeout(() => {
        window.location.href = `{{ route('reguler.my-class.classroom', [$classroom->id]) }}`; // Rute untuk kembali
    }, 1000);
}


</script>


  </div>
</div>

@endsection