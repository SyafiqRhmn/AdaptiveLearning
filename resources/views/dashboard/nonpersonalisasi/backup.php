@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-4">
        <div class="row">
          <div class="col">
              <div class="card p-4 position-relative">
                  <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                      <button class="btn btn-dark ml-auto" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                      </button>
                      <!-- <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        @if ($test !== null)
                        <li><a class="dropdown-item" href="{{ route('reguler.test.do', ['test' => 'post-test','classroomID' => $classroomID]) }}">Kerjakan post test</a></li>

                        @endif
                          <li><a class="dropdown-item" href="{{ route('reguler.classroom.out', ['classroomID' => $classroomID]) }}" onclick="return confirm('Apakah anda yakin ingin keluar dari kelas ini?')">Keluar kelas</a></li>
                      </ul> -->
                  </div>
                  <h3>{{ $class->name }}</h3>
@foreach ($subjects as $subject)
    <a href="{{ route('reguler.classroom.modul', ['subjectID'=> $subject->subject_id]) }}" class="btn btn-outline-secondary">
        Materi {{ $loop->iteration }}: {{ $subject->subject->name ?? Str::limit($subject->subject->deskripsi, 60, '. . .') }}
    </a>
@endforeach

                  <!-- @if ($test !== null)
                      <h3>{{ $class->name }}</h3>
                      {{-- @if (isset($subjects[0]->subject->subject))     --}}
                          @foreach ($subjects as $subject)
                          <a href="{{ route('reguler.classroom.modul', ['subjectID'=> $subject->subject_id]) }}" class="btn btn-outline-secondary text-left my-2 p-2" onClick="startTimer({{ $subject->id }})" data-subject-id="{{ $subject->id }}">
                                  Materi {{ $loop->iteration }}:
                                  @if ($subject->subject->name !== null)
                                      {{ $subject->subject->name }}
                                  @else
                                      {!! Str::limit($subject->subject->deskripsi, rand(20,60), '. . .') !!}
                                  @endif
                              </a>
                          @endforeach
                      {{-- @else
                          <p>belum ada materi.</p>
                      @endif --}}
                  @else
                  
                      <p class="text-center">Anda belum mengerjakan pre-test Mata Kuliah {{ $title }}, silahkan mengerjakannya terlebih dahulu</p>
                      <form action="{{ route('reguler.save_modules') }}" method="post">
                        @csrf
                        @method('PUT')
                      <p> Silahkan Input Modul Awal : </p>
                      <select name="startSubject" id="startSubject" onchange="fetchSubjects()"> @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                      </select>
                      <p> Silahkan Input Modul Tujuan :</p>
                      <select name="endSubject" id="endSubject" onchange="fetchSubjects()"> @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                      </select>
                      <p> </p>
                      <ul id="moduleList"></ul> 
                      <button type="submit">Simpan Pilihan</button>
                      @if ($startSubject !== null)
                      <a onclick="startPretest()" class="btn btn-primary text-center">Kerjakan pre test</a>
                      @else
                      <p class="text-center"> anda belum memilih modul</p>
                      @endif
                  @endif
                      </form> -->
              </div>
          </div>        
        </div>
    </div>


  </div>
</div>
<ul id="moduleList"></ul> <!-- Placeholder for module list -->

<script>
  document.getElementById('startSubject').addEventListener('change', function() {
    fetchSubjects();
  });

  document.getElementById('endSubject').addEventListener('change', function() {
    fetchSubjects();
  });

  // Fungsi lainnya
  function fetchSubjects() {
    const startSubject = document.getElementById('startSubject').value;
    const endSubject = document.getElementById('endSubject').value;

    // Kirim permintaan ke server untuk mendapatkan data modul antara startSubject dan endSubject
    fetch(`/reguler/get-modules?start=${startSubject}&end=${endSubject}`)
      .then(response => response.json())
      .then(data => {
        // Perbarui tampilan dengan data modul yang diterima dari server
        updateModuleList(data);
      })
      .catch(error => console.error('Error:', error));
  }

  function updateModuleList(modules) {
    const moduleList = document.getElementById('moduleList');
    moduleList.innerHTML = ''; // Hapus konten sebelumnya

    // Tambahkan modul baru ke daftar modul
    modules.forEach(module => {
      const moduleItem = document.createElement('li');
      moduleItem.textContent = module.name;
      moduleList.appendChild(moduleItem);
    });
  }

function startTimer(subjectId) {
  console.log(subjectId);
  // Lakukan permintaan PUT untuk memulai timer
  fetch(`{{ url('reguler/start-timer') }}/${subjectId}`, {
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
function startPretest(startSubject, endSubject) {
      fetchSubjects(); // Panggil fetchSubjects() sebelum mengarahkan ke halaman pretest

      const confirmed = confirm('Anda yakin ingin mengerjakan pre-test ini? \nMohon persiapkan test dengan baik');
      if (confirmed) {
          const url = "{{ route('reguler.test.do', ['test' => 'pre-test', 'classroomID' => $classroomID]) }}";
          window.location.href = url;
      }
      return false; // Prevent default link behavior
  }
</script>

@endsection