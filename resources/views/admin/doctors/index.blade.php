<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<x-layout>
  <div class="container">
    <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
      <div class="breadcrumb-container">
      <div class="breadcrumb-container mb-2">
      <a href="/admin/dashboard" class="breadcrumb-item first">
        <i class="bi bi-house-fill"></i>
      </a>
      <a href="#" class="breadcrumb-item">
        <i class="bi bi-beaker"></i> Kelola Dokter
      </a>
    </div>
  </nav>
    @if(session('success'))
      <script>
        iziToast.success({
          title: 'Sukses',
          message: '{{ session('success') }}',
          position: 'topCenter'
        });
      </script>
    @endif
    <div class="px-4 py-4 bg-light rounded-3">
      <div class="container-fluid">
        <button class="btn btn-primary fw-bold mb-3" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
          Tambah Dokter
        </button>
        <div class="table-responsive">
          <table class="table" id="doctors-table">
            <thead class="table-dark">
              <tr>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>Institution</th>
                <th>Username</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($doctors as $doctor)
                <tr>
                  <td>{{ $doctor->nama }}</td>
                  <td>{{ $doctor->spesialis }}</td>
                  <td>{{ $doctor->institution }}</td>
                  <td>{{ $doctor->username }}</td>
                  <td>
                    <center>
                      <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doctor->id }}">Edit</button>
                      <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                      </form>
                    </center>
                  </td>
                </tr>
              @empty
              @endforelse
            </tbody>
          </table>
        </div>

        @foreach($doctors as $doctor)
          <div class="modal fade" id="editDoctorModal{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
              <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label>Nama</label>
                      <input type="text" name="nama" class="form-control" value="{{ $doctor->nama }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Spesialis</label>
                      <input type="text" name="spesialis" class="form-control" value="{{ $doctor->spesialis }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Institution</label>
                      <input type="text" name="institution" class="form-control" value="{{ $doctor->institution }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Username</label>
                      <input type="text" name="username" class="form-control" value="{{ $doctor->username }}" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        @endforeach
      </div>
  </div>
</x-layout>

<!-- Modal Tambah Dokter -->
<div class="modal fade" id="createDoctorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.doctors.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Dokter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Putry" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="putry@gmail.com" required>
          </div>
          <div class="form-group mb-3">
            <label for="institution">Instansi</label>
            <input type="text" name="institution" class="form-control" id="institution" placeholder="Contoh: RSUD Bandung">
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Putry21" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="123456" required>
            <div class="form-text text-muted">Password minimum 6 letters</div>
          </div>
          <div class="mb-3">
            <label>Spesialis</label>
            <input type="text" name="spesialis" class="form-control" placeholder="Kanker" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function () {
    var table = $('#doctors-table').DataTable({
      responsive: true,
      language: {
        search: "Cari:",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        paginate: {
          previous: "Sebelumnya",
          next: "Berikutnya"
        },
        zeroRecords: "Tidak ada data ditemukan"
      }
    });
  });
</script>
<style>
  .dataTables_filter input {
    max-width: 200px;
  }
</style>
