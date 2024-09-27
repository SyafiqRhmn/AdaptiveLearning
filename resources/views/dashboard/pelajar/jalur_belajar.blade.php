@extends('layouts.main')
@section('container')

<div class="wrapper d-flex align-items-stretch">
  @include('partials.sidebar')
  <div id="content" class="p-md-3">
    @include('partials.navbar_guru')


    <div class="container my-2">
        <div class="row">
            <div class="col">
              <div class="card p-4">

    <div id="loading" class="loading-overlay">
      <div class="loading-spinner"></div>
    </div>
    <b>Berikut adalah modul-modul yang harus Anda pelajari:</b>
    <p id="moduls"></p>
    <a href="{{ route('adaptive.my-class') }}" class="btn btn-success w-25">kelas saya</a>

              </div>
            </div>
        </div>
    </div>

    <div id="loading" style="display: none;"></div>

    <script>
    const routeUrl = "{{ route('adaptive.susun-jalur-pembelajaran') }}";

    document.getElementById('loading').style.display = 'block';

    fetch(routeUrl)
        .then(response => response.json())
        .then(data => {
            console.log(data);  // Cetak data ke konsol untuk debug
            document.getElementById('moduls').innerHTML = data.message;
            document.getElementById('loading').style.display = 'none';
        })
        .catch(error => {
            console.error(error);
            document.getElementById('loading').style.display = 'none';
        });
</script>
  </div>
</div>

@endsection