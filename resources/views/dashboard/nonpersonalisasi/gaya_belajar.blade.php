@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')

<div class="container">
    <h2>Hasil Gaya Belajar</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Gaya Belajar</th>
                <th>Nama</th>
                <th>NIM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hasilGayaBelajar as $hasil)
                <tr>
                <td>
                    @if ($hasil->gaya_belajar === 'V')
                        Visual
                    @elseif ($hasil->gaya_belajar === 'A')
                        Auditori
                    @elseif ($hasil->gaya_belajar === 'K')
                        Kinestetik
                    @else
                        Tidak diketahui
                    @endif
                    </td>
                    <td>{{ $hasil->user->name ?? 'Tidak ada nama' }}</td>
                    <td>{{ $hasil->user->nim ?? 'Tidak ada NIM' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
