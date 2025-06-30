<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Poppins', sans-serif;
    background-color: #f6fbf7 !important;
  }
  .button-login {
    background-color: #175e54 !important;
    color: white !important;
  }
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
<x-layout title="Login">
  <div id="particles-js"></div>
  <div class="row">
    <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center">
      <img src="{{ asset('images/login.svg') }}" alt="Login Image"
        style="object-fit: cover; border-radius: 0.5rem; width: 90%;">
    </div>
    <div class="col-md-4">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
      <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px; background-color: #f6fbf7;">
        <h4 class="mb-4 text-center">Login</h4>
        <form method="POST" action="/login">
          @csrf
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user"></i></span>
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
          </div>
          <div class="mb-4">
            <label for="role" class="form-label">Pilih Peran</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
              <select class="form-select" id="role" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Nakes</option>
                <option value="dokter">Dokter</option>
                <option value="admin_pemerintah">Admin Pemerintahan</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn w-100 button-login">Masuk</button>
        </form>
      </div>
    </div>
  </div>
</x-layout>
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