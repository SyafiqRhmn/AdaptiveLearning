<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    {{-- ckeditor --}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

    @isset(Auth::user()->role)    
        @if (Auth::user()->role === 'guru' || Auth::user()->role === 'pakar' || Auth::user()->role === 'pelajar')
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
          <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        @endif
    @endisset
    
    <title>{{ $title }}</title>
  </head>
  <body class="d-flex flex-column" style="height: fit-content">

    @can('guru')
      {{-- @include('partials.navbar_guru') --}}
    @elsecan('pakar')
      {{-- @include('partials.navbar_guru') --}}
    @elsecan('pelajar')
      {{-- @include('partials.navbar_guru') --}}
    @else()  
      @include('partials.navbar')
    @endcan

      <div class="container-fluid" id="content">
        @yield('container')
      </div>
    
    @can('guru')
      {{-- @include('partials.sidebar') --}}
    @elsecan('pakar')
      {{-- @include('partials.sidebar') --}}
    @elsecan('pelajar')
      {{-- @include('partials.sidebar') --}}
    @else()  
      @include('partials.footer')
    @endcan

    {{-- js untuk sidebar guru --}}
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
      function btnConfirmLogout() {
        let text = "Are you sure?";
        if (confirm(text) == true) {
          return true;
        } else {
          return false;
        }
        document.getElementById("demo").innerHTML = text;
      }
      function showPassword() {
        var x = document.getElementById("password");
        var y = document.getElementById("password_confirmation");
        var z = document.getElementById("current_password");
        if (x.type === "password") {
          x.type = "text";
          y.type = "text";
          z.type = "text";
        } else {
          x.type = "password";
          y.type = "password";
          z.type = "password";
        }
      }
      </script>
  </body>
</html>