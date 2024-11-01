<nav class="navbar navbar-expand-lg navbar-light bg-dark-subtle px-5 py-2">
  <div class="container-fluid">
    <!-- Logo di kiri -->
    <a class="navbar-brand" href="/"><img src="{{ asset('assets/images/utm-logo.png') }}" width="50" height="50" alt="Logo"></a>
    
    <!-- Menu di tengah -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        @can('admin')
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/pelajar">Data Pelajar</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/dosen">Data Dosen</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/pakar">Data Pakar</a>
          </li>
        @endcan
      </ul>
    </div>
    
    <!-- Login/Register di kanan -->
    <div class="d-flex">
      @auth
      <a href="/logout" onclick="return confirm('Apakah anda yakin ingin logout?')" class="btn btn-outline-warning">Logout</a>
      @else
      <a href="/login" class="btn btn-outline-primary me-2">Login</a>
      <a href="/register" class="btn btn-primary">Register</a>
      @endauth
    </div>
  </div>
</nav>
