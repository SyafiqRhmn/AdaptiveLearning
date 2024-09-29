<nav id="sidebar">
    <div class="p-4 pt-5">
      <a href="#" class="img logo" style="background-image: url({{ asset('assets/images/utm-logo.png') }});"></a>
      <p class="mb-2 text-center">welcome, 
        {{-- {{ Auth::user()->role->name }} --}}
        {{ Auth::user()->name }}!</p>
        @can('guru')
            <ul class="list-unstyled components mb-5">
                <li class="{{ Request::is('classroom') ? 'active' : '' }}">
                    <a href="/classroom">Classroom</a>
                </li>
                <li class="{{ Request::is('subject') ? 'active' : '' }}">
                    <a href="/subject">Subject/Modul</a>
                </li>
                <li class="{{ Request::is('test*') ? 'active' : '' }}">
                    <a href="#pagetests" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle">test</a>
                    <ul class="collapse list-unstyled {{ Request::is('test*') ? 'show' : '' }}" id="pagetests">
                    <li class="{{ Request::is('test/pre-test*') ? 'active' : '' }}">
                        <a href="/test/pre-test">Pre Test</a>
                    </li>
                    <li class="{{ Request::is('test/post-test*') ? 'active' : '' }}">
                        <a href="/test/post-test">Post Test</a>
                    </li>
                    <li class="{{ Request::is('test/course-test*') ? 'active' : '' }}">
                        <a href="/test/course-test">Course Test</a>
                    </li>
                    <li class="{{ Request::is('test/test-jawaban*') ? 'active' : '' }}">
                        <a href="/test/soal-jawaban">soal + Jawaban</a>
                    </li>
                    <li class="{{ Request::is('test/question*') ? 'active' : '' }}">
                        <a href="/test/question">soal</a>
                    </li>
                    <li class="{{ Request::is('test/answer*') ? 'active' : '' }}">
                        <a href="/test/answer">Jawaban</a>
                    </li>
                    </ul>
                </li>
                <li class="{{ Request::is('hasil-test-pelajar') ? 'active' : '' }}">
                    <a href="/hasil-test-pelajar">Hasil Test Pelajar</a>
                </li>
            </ul>
        @endcan
        @can('pelajar')
        @php
            if (Auth::user()->tipe=='reguler'){
                $route='/reguler';
            }
            else{
                $route='/adaptive';
            }
        @endphp

            <ul class="list-unstyled components mb-5">
                <li class="{{ Request::is('kuisioner') || Request::is('dashboard') ? 'active' : '' }}">
                    <a href=<?= $route . "/kuisioner" ?>>Kuisioner</a>
                </li>
                <li class="{{ Request::is('my-class') || Request::is('dashboard') ? 'active' : '' }}">
                    <a href=<?= $route . "/my-class" ?>>Kelas Saya</a>
                </li>
                <li class="{{ Request::is('all-class') ? 'active' : '' }}">
                    <a href=<?= $route . "/all-class" ?>>Semua Kelas</a>
                </li>
                <li class="{{ Request::is('hasil-test-saya') ? 'active' : '' }}">
                    <a href=<?= $route . "/hasil-test-saya" ?>>Hasil Test Saya</a>
                </li>
                <li class="{{ Request::is('setting-akun') ? 'active' : '' }}">
                    <a href=<?= $route . "/setting-akun" ?>>Setting Akun</a>
                </li>
            </ul>

        @endcan
<div class="footer">
    <p>Terjadi error? kontak admin : 085852338174 </p>
</div>

</div>
</nav>