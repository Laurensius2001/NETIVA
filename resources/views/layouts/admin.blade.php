<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Netiva</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Fonts & CSS --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    html, body {
      height: 100%;
      margin: 0;
    }

    .sidebar {
      width: 400px;
      background: #175e54;
      color: #000;
      transition: all 0.3s ease;
      box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1000;
      overflow-y: auto;
    }

    .sidebar.collapsed {
      width: 100px;
    }

    .sidebar .nav-link {
      font-size: 1.2rem;
      padding: 1rem 1.25rem;
      color: white;
      white-space: nowrap;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 400 !important;
    }

    .sidebar .nav-link.active {
      background-color: #eef8f0 !important;
      color: #000 !important;
    }

    .sidebar.collapsed .nav-link span,
    .sidebar.collapsed .user-full {
      display: none !important;
    }

    .sidebar.collapsed .user-icon-only {
      display: block !important;
      text-align: center;
    }

    .sidebar .logo {
      height: 80px;
      display: flex;
      align-items: center;
      padding: 0 1.25rem;
      z-index: 1100;
      position: sticky;
      top: 0;
    }

    .logo-full { display: inline-block; }
    .logo-short { display: none; font-size: 1.8rem; font-weight: bold; color: white; }

    .sidebar.collapsed .logo-full { display: none; }
    .sidebar.collapsed .logo-short { display: inline-block; }

    .sidebar .mt-auto {
      margin-top: auto;
      width: 100%;
    }

    .topbar {
      background-color: #eef8f0;
      color: #175e54;
      padding: 1.2rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
      position: sticky;
      top: 0;
      z-index: 1020; /* ubah dari 1020 jadi lebih tinggi dari sidebar */
    }


    .main-content {
      margin-left: 400px;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: margin-left 0.3s ease;
      
    }

    .sidebar.collapsed + .main-content {
      margin-left: 100px !important;
    }

    .scrollable-content {
      flex-grow: 1;
      overflow-y: auto;
      padding: 1.5rem;
      background-color: #eef8f0;
    }

    .user-icon-only {
      display: none;
    }

    .user-info {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
      }


    @media (max-width: 768px) {
      .sidebar {
        width: 280px;
        left: -280px;
        position: fixed;
        top: 80px; /* ganti dari 0 jadi 80px */
        bottom: 0;
        background-color: #fff;
        transition: left 0.3s ease;
        z-index: 1050;
      }

      .sidebar.mobile-show {
        left: 0;
      }

      .main-content {
        margin-left: 0 !important;
      }

      .scrollable-content {
        margin-top: 80px;
      }
    }


    .disabled-link {
      pointer-events: none;
      cursor: default;
    }

    .hamburger {
      cursor: pointer;
      padding: 8px;
      display: inline-block;
    }
  </style>
</head>

<body>
  {{-- Sidebar --}}
  <div class="sidebar d-flex flex-column p-3" id="sidebar">
    @auth
      <div class="logo mb-3 text-decoration-none disabled-link">
        <span class="logo-full"><img src="{{ asset('images/logo.png') }}" style="filter: brightness(0) invert(1);" alt="Netiva" height="30"></span>
        <span class="logo-short">N</span>
      </div>
    @else
      <a href="/" class="logo mb-3 text-decoration-none">
        <span class="logo-full"><img src="{{ asset('images/logo.png') }}" style="filter: brightness(0) invert(1);" alt="Netiva" height="30"></span>
        <span class="logo-short">N</span>
      </a>
    @endauth

    <ul class="nav nav-pills flex-column mb-auto">
      @auth
        @if(auth()->user()->role === 'admin')
          <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house-chimney"></i><span>Dashboard Nakes</span></a></li>
          <li><a href="{{ route('admin.doctors.index') }}" class="nav-link {{ request()->is('admin/doctors*') ? 'active' : '' }}"><i class="fa-solid fa-user-nurse"></i><span>Kelolah Dokter</span></a></li>
          <li><a href="{{ route('admin.patients.index') }}" class="nav-link {{ request()->is('admin/patients*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i><span>Kelolah Pasien</span></a></li>
          <li><a href="{{ route('admin.citra.index') }}" class="nav-link {{ request()->is('admin/citra*') ? 'active' : '' }}"><i class="fa-solid fa-images"></i><span>Kelolah Citra</span></a></li>
        @elseif(auth()->user()->role === 'dokter')
          <li><a href="{{ route('dokter.dashboard') }}" class="nav-link {{ request()->is('dokter/dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house-chimney"></i><span>Dashboard Dokter</span></a></li>
        @endif

        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="nav-link btn btn-link text-black text-white"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></button>
          </form>
        </li>
      @endauth
    </ul>

    {{-- User Info --}}
    <div class="mt-auto user-info p-3 rounded shadow-lg d-flex flex-column align-items-start" style="background-color: #eef8f0;">
      <div class="user-full d-flex align-items-center">
        <i class="fa-solid fa-user-secret me-2 fs-5"></i>
        <div>
          <small class="text-muted">Logged in as:</small><br>
          <strong class="text-black">{{ auth()->user()->name ?? 'Admin Netiva' }}</strong>
        </div>
      </div>
      <div class="user-icon-only d-none">
        <i class="bi bi-person-circle fs-4"></i>
      </div>
    </div>

  </div>

  {{-- Main Content --}}
  <div class="main-content" id="mainContent">
    <div class="topbar">
      <div class="hamburger fw-bold lead" id="sidebarToggle">
        <i class="bi bi-list" id="hamburgerIcon"></i>
      </div>
      <div class="fw-bold lead"><i class="fa-solid fa-user-secret me-2 fs-5"></i> {{ auth()->user()->name ?? 'Admin' }}</div>
    </div>

    <div class="scrollable-content">
      @yield('content')
    </div>
  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
  <script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const hamburgerIcon = document.getElementById('hamburgerIcon');

    toggleBtn.addEventListener('click', () => {
      const isMobile = window.innerWidth <= 768;

      if (isMobile) {
        sidebar.classList.toggle('mobile-show');
      } else {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
      }

      // Ganti ikon
      if (sidebar.classList.contains('mobile-show') || sidebar.classList.contains('collapsed')) {
        hamburgerIcon.classList.remove('bi-list');
        hamburgerIcon.classList.add('bi-x-lg');
      } else {
        hamburgerIcon.classList.remove('bi-x-lg');
        hamburgerIcon.classList.add('bi-list');
      }
    });
  </script>
</body>
</html>
