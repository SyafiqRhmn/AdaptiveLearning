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
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" 
    href="{{ route('reguler.test.do', ['test' => 'post-test', 'classroomID' => $classroomID]) }}"
    @if(!$canTakePostTest) 
        onclick="alert('Anda harus menonton semua modul selama minimal 20 menit sebelum bisa mengerjakan post-test.'); return false;"
    @endif>
    Kerjakan post-test
</a>

                                <li><a class="dropdown-item" href="{{ route('reguler.classroom.out', ['classroomID' => $classroomID]) }}" onclick="return confirm('Apakah anda yakin ingin keluar dari kelas ini?')">Keluar kelas</a></li>
                            </ul>
                        </div>
                        <h3 class="mb-3">{{ $class->name }}</h3> <!-- Menambahkan margin bawah pada judul -->

                        {{-- Cek apakah pre-test sudah dikerjakan --}}
                        @if ($pretest)
                            {{-- Jika pre-test sudah dikerjakan, tampilkan semua modul --}}
                            @foreach ($subjects as $subject)
                                <a href="{{ route('reguler.classroom.modul', ['subjectID' => $subject->id]) }}" class="btn btn-outline-secondary text-left my-2 p-2" onClick="startTimer({{ $subject->id }})" data-subject-id="{{ $subject->id }}">
                                    Materi {{ $loop->iteration }}: {{ $subject->name ?? Str::limit($subject->deskripsi, rand(20,60), '. . .') }}
                                </a>
                            @endforeach
                        @else
                            {{-- Jika pre-test belum dikerjakan, tampilkan pesan peringatan --}}
                            <p class="text-danger mb-3">
                                Anda belum mengerjakan pre-test. Silakan kerjakan pre-test terlebih dahulu.
                            </p>
                            <form action="" method="post">
                                @csrf
                                @method('PUT')
                                <a onclick="startPretest()" class="btn btn-primary text-center mb-3">Kerjakan pre test</a> <!-- Menambahkan margin bawah -->
                            </form>
                            {{-- Tetap tampilkan semua modul meski pre-test belum dikerjakan --}}
                            @foreach ($subjects as $subject)
                                <a href="#" class="btn btn-outline-secondary text-left my-2 p-2" disabled>
                                    Materi {{ $loop->iteration }}: {{ $subject->name ?? Str::limit($subject->deskripsi, rand(20,60), '. . .') }} (Terkunci)
                                </a>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<ul id="moduleList" class="mt-4"></ul> <!-- Placeholder for module list with margin top -->

<script>
    // document.getElementById('startSubject').addEventListener('change', function() {
    //     fetchSubjects();
    // });

    // document.getElementById('endSubject').addEventListener('change', function() {
    //     fetchSubjects();
    // });

    // // Fungsi lainnya
    // function fetchSubjects() {
    //     const startSubject = document.getElementById('startSubject').value;
    //     const endSubject = document.getElementById('endSubject').value;

    //     // Kirim permintaan ke server untuk mendapatkan data modul antara startSubject dan endSubject
    //     fetch(`/reguler/get-modules?start=${startSubject}&end=${endSubject}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             // Perbarui tampilan dengan data modul yang diterima dari server
    //             updateModuleList(data);
    //         })
    //         .catch(error => console.error('Error:', error));
    // }

    // function updateModuleList(modules) {
    //     const moduleList = document.getElementById('moduleList');
    //     moduleList.innerHTML = ''; // Hapus konten sebelumnya

    //     // Tambahkan modul baru ke daftar modul
    //     modules.forEach(module => {
    //         const moduleItem = document.createElement('li');
    //         moduleItem.textContent = module.name;
    //         moduleList.appendChild(moduleItem);
    //     });
    // }

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

    function startPretest() {
        const confirmed = confirm('Anda yakin ingin mengerjakan pre-test ini? \nMohon persiapkan test dengan baik');
        if (confirmed) {
            const url = "{{ route('reguler.test.do', ['test' => 'pre-test', 'classroomID' => $classroomID]) }}";
            window.location.href = url;
        }
        return false; // Prevent default link behavior
    }
</script>

@endsection
