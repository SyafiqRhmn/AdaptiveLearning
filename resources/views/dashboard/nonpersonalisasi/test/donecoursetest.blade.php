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
                <center><b>Selamat!</b> Telah selesai mengerjakan soal {{ $nama_test }}. <br>
                Skor anda adalah: <br><br>
                <span class="text-center bg-info p-3 m-2 fs-1 rounded-3 text-light">{{ $skor }}</span> <br><br><br>
                <a href="/" class="btn btn-success">Test Selesai</a> <br>
                <!-- <i style="color: red">*Silahkan klik tombol diatas untuk menyusun jalur pembelajaran yang sesuai dengan kompetensimu.</i> -->
                </center>
              </div>              
            </div>
        </div>
    </div>


  </div>
</div>

@endsection