<x-layout title="Login">
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
      <h4 class="mb-4 text-center">Login</h4>
      <form method="POST" action="/login">

        @csrf

        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" id="username" name="username" required autofocus>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="mb-4">
          <label for="role" class="form-label">Pilih Peran</label>
          <select class="form-select" id="role" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin">Admin</option>
            <option value="dokter">Dokter</option>
            <option value="pasien">Pasien</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">Masuk</button>
      </form>
    </div>
  </div>
</x-layout>
