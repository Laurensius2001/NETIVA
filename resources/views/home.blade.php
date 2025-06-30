<x-layout title="Home">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      position: relative;
    }
    #particles-js {
      position: fixed;
      top: 0; left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      background-color: #f6fbf7 !important;
    }
  </style>
  <div id="particles-js"></div>
  <div class="d-flex flex-column min-vh-100">

    {{-- Section Beranda --}}
    <section id="beranda" style="scroll-margin-top: 100px;">
      <div class="gradient-bg container py-5">
        <div class="row align-items-center">
          <div class="col-12 col-md-6 text-start mb-4 mb-md-0">
            <span class="badge rounded-pill px-4 py-2 mb-4" style="background-color: #eef8f0; font-size:16px; color: #175e54; font-weight: 700;">
              SELALU JAGA KESEHATAN !
            </span>
            <h1 class="display-5 fw-bold">Selamat Datang di Sistem Deteksi Kanker Serviks Netiva</h1>
            <p class="lead mt-3 text-secondary">
              Ivanet memiliki tujuan untuk membuat dan mengembangkan aplikasi yang memudahkan tenaga medis dalam memeriksa dan memonitoring sebaran kanker serviks..
            </p>
            <a href="{{ route('login') }}" class="btn btn-lg mt-3 text-white fw-bold" style="background-color: #175e54;">Mulai Sekarang</a>
          </div>
          <div class="col-12 col-md-6 text-center">
            <img src="{{ asset('images/home.svg') }}" alt="Ilustrasi Dokter" class="img-fluid" style="max-height: 400px;">
          </div>
        </div>
      </div>
    </section>


    {{-- Section Tentang --}}
   <section id="tentang" class="py-5 rounded mb-5 mb-md-0" style="scroll-margin-top: 100px;">
      <div class="container-fluid px-2">
        <div class="p-4 rounded-4" style="background-color: #f6fbf7;">
          <div class="text-center mb-4">
            <span class="badge rounded-pill px-3 py-2" style="background-color: #eef8f0; color: #175e54; font-weight: 700; font-size:16px;">
              TENTANG NETIVA
            </span>
            <p class="lead mt-3 text-secondary">
              Netiva untuk Deteksi dan Pemantauan Kanker Serviks
            </p>
          </div>

          <div class="row g-4 mt-3">
            <div class="col-md-4">
              <div class="card h-100 rounded p-4 border-0 shadow-lg" style="background-color: #f6fbf7;">
                <div class="mb-3">
                  <img src="/images/1.png" alt="Deteksi Pra Kanker" style="height: 48px;">
                </div>
                <h5 class="fw-bold lead">Deteksi Pra Kanker Serviks</h5>
                <p class="text-muted lead">Netiva mendukung deteksi dini kanker serviks melalui deteksi pada SSK (sambungan skuamo kolumnar) dan tes IVA (Inspeksi Visual dengan Asam Asetat).</p>
                <a href="#" class="text-success text-decoration-none d-inline-flex align-items-center">
                </a>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card h-100 rounded p-4 border-0 shadow-lg" style="background-color: #f6fbf7;">
                <div class="mb-3">
                  <img src="/images/2.png" alt="Pemetaan Hasil IVA" style="height: 48px;">
                </div>
                <h5 class="fw-bold">Pemetaan Hasil Tes IVA</h5>
                <p class="text-muted lead">Website Netiva menampilkan sebaran hasil tes IVA berdasarkan wilayah kecamatan dalam bentuk Peta, Tabel, dan Grafik.</p>
                <a href="#" class="text-success text-decoration-none d-inline-flex align-items-center">
                </a>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card h-100 rounded p-4 border-0 shadow-lg" style="background-color: #f6fbf7;">
                <div class="mb-3">
                  <img src="/images/3.png" alt="Analisis Citra AI" style="height: 48px;">
                </div>
                <h5 class="fw-bold">Analisis Citra AI</h5>
                <p class="text-muted lead">Netiva memungkinkan dokter untuk menggambar dan memperbarui anotasi pada citra serviks, termasuk hasil sebelum, sesudah, dan citra AI.</p>
                <a href="#" class="text-success text-decoration-none d-inline-flex align-items-center">
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- Footer --}}
    
  </div>
</x-layout>
  <footer class="text-white py-4 mt-auto" style="background-color: #175e54;">
    <div class="container text-center">
      <a class="navbar-brand d-inline-block mb-3" href="/">
        <img src="{{ asset('images/logo.png') }}" alt="Netiva" height="30" style="filter: brightness(0) invert(1);">
      </a>
      <p class="mb-1">Jl. Telekomunikasi - Ters. Buah Batu Bandung 40257 Indonesia</p>
      <p class="mb-1">Telephone: (022) 7566456</p>
      <p class="mb-1">Email: info@ivanet.id</p>
      <hr class="bg-light" style="opacity: 0.3;">
      <p class="mb-0">&copy; 2020 Ivanet. All rights reserved.</p>
    </div>
  </footer>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
  particlesJS('particles-js', {
    "particles": {
      "number": { "value": 60 },
      "shape": { "type": "circle" },
      "size": { "value": 20, "random": true },
      "move": { "enable": true, "speed": 2 },
      "line_linked": {
        "enable": true,
        "color": "#555555",                    // Warna garis penghubung gelap
        "opacity": 0.5,
        "width": 1
      }
    },
    "interactivity": {
      "events": {
        "onhover": {
          "enable": true,
          "mode": "repulse"
        }
      }
    }
  });
</script>
