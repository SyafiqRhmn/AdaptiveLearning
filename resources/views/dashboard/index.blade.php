@extends('layouts.main')
@section('container')
<!-- Jumbotron -->
<div class="jumbotron my-4">
<div class="container-fluid text-center text-white" style="background-image: url({{ asset('assets/images/utm.jpg') }}); background-size: cover; background-repeat: no-repeat; background-position: center center; height: 100vh; display: flex; justify-content: center; align-items: center;">
    <h1> E-Learning <br>Universitas Trunojoyo Madura <br>
        @auth
            <a class="btn btn-primary btn-lg" href="/dashboard" role="button">Dashboard</a>
        @else
            <a class="btn btn-primary btn-lg" href="/login" role="button">Login</a>
        @endauth
    </h1>
</div>
</div>

@endsection