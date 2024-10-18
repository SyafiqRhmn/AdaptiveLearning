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
              @if($isModulTerbuka)
                <h3>{{ $classroom->name }}</h3>
                @if($ranking['V'] == 1)
                <a href="{{ route('reguler.pdf', ['subjectID' => $subject->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;" onclick="startTimer('{{ $subject->id }}');">Visual</a>
                @elseif($ranking['A'] == 1)
                <a href="{{ route('reguler.video_link', ['subjectID' => $subject->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;"onclick="videostartTimer('{{ $subject->id }}');">Auditori</a>
                @elseif($ranking['K'] == 1)
                <a href="{{ route('reguler.interpreter', ['subjectID' => $subject->id]) }}" class="btn btn-primary mt-4" style="max-width: 110px;"onclick="interpreterstartTimer('{{ $subject->id }}');">Kinestetik</a>
                @endif
              @else
                  <p>Modul belum terbuka.</p>
              @endif
              </div>              
            </div>
        </div>
    </div>
<script>
  function startTimer(subjectId) {
  console.log(subjectId);
  // Lakukan permintaan PUT untuk memulai timer
  fetch(`{{ url('adaptive/start-timer') }}/${subjectId}`, {
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
function videostartTimer(subjectId) {
  console.log(subjectId);
  // Lakukan permintaan PUT untuk memulai timer
  fetch(`{{ url('adaptive/start-timer-video') }}/${subjectId}`, {
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
function interpreterstartTimer(subjectId) {
  console.log(subjectId);
  // Lakukan permintaan PUT untuk memulai timer
  fetch(`{{ url('adaptive/start-timer-interpreter') }}/${subjectId}`, {
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
</script>

  </div>
</div>

@endsection