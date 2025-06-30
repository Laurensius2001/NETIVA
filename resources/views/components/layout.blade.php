<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Netiva</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Bootstrap --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg fixed-top shadow-sm px-3 py-3"style="background-color: #f6fbf7;">
    <div class="container">
      <a class="navbar-brand" href="/"><img src="{{ asset('images/logo.png') }}" alt="Netiva" height="30"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

        @auth
        @if(auth()->user()->role === 'admin')
        <li class="nav-item linkNew"><a class="nav-link" href="/admin/dashboard">Dashboard Nakes</a></li>
        <li class="nav-item linkNew"><a class="nav-link" href="{{ route('admin.doctors.index') }}">Kelola Dokter</a></li>
        <li class="nav-item linkNew"><a class="nav-link" href="{{ route('admin.patients.index') }}">Kelola Pasien</a></li>
        <li class="nav-item linkNew"><a class="nav-link" href="{{ route('admin.citra.index') }}">Kelola Citra Pasien</a></li>
        @elseif(auth()->user()->role === 'dokter')
        <li class="nav-item linkNew"><a class="nav-link" href="/dokter/dashboard">Dashboard Dokter</a></li>
        @elseif(auth()->user()->role === 'pasien')
        <li class="nav-item linkNew"><a class="nav-link" href="/pasien/dashboard">Dashboard Pasien</a></li>
        @endif
        <li class="nav-item">
          <a class="nav-link linkNew" href="{{ route('logout') }}"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
        </a>
        </li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>

      @else
      <li class="nav-item"><a class="nav-link link-home" href="#beranda">Beranda</a></li>
      <li class="nav-item"><a class="nav-link link-home" href="#tentang">Tentang Netiva</a></li>
      <li class="nav-item"><a class="nav-link link-home" href="{{ route('login') }}">Login</a></li>
      @endauth
        </ul>

      </div>
    </div>
  </nav>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
      const navbarCollapse = document.querySelector('.navbar-collapse');

      navLinks.forEach(function(link) {
        link.addEventListener('click', function () {
          const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
            toggle: false
          });
          bsCollapse.hide();
        });
      });
    });
  </script>


  @if ($errors->any())
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      @foreach ($errors->all() as $error)
        iziToast.error({
          title: 'Login Gagal',
          message: '{{ $error }}',
          position: 'topCenter', // Ganti ke tengah atas
          timeout: 4000,
          transitionIn: 'fadeInDown',
          transitionOut: 'fadeOutUp'
        });
      @endforeach
    });
  </script>
  @endif

  {{-- Content --}}
  <main class="container mt-5 pt-5">
    {{ $slot }}
  </main>
  <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>