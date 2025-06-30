@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <div class="container  py-4">
    <span class="badge rounded-pill px-4 py-2 mb-4 shadow-lg" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
      <i class="fa-solid fa-user-nurse"></i> Halaman Kelolah Dokter
    </span>
    @if(session('success'))
      <script>
        iziToast.success({
          title: 'Sukses',
          message: '{{ session('success') }}',
          position: 'topCenter'
        });
      </script>
    @endif
    <div class="px-4 py-4 h-100 rounded p-4 border-0 shadow-lg" style="background-color: #f6fbf7;">
      <div class="container-fluid">
        <button class="btn mb-3 fw-bold tombol-add text-white" data-bs-toggle="modal" data-bs-target="#createDoctorModal">
          <i class="bi bi-plus-circle me-1"></i> Tambah Dokter
        </button>
        <div class="table-responsive">
          <table class="table" id="doctors-table">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Spesialis</th>
                <th>Institution</th>
                <th>Username</th>
                <th>Email</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($doctors as $doctor)
                <!-- Modal Konfirmasi Hapus Dokter -->
                <div class="modal fade" id="confirmDeleteDoctor{{ $doctor->id }}" tabindex="-1" aria-labelledby="deleteDoctorLabel{{ $doctor->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteDoctorLabel{{ $doctor->id }}">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                      </div>
                      <div class="modal-body">
                        Yakin ingin menghapus dokter <strong>{{ $doctor->nama }}</strong>?
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
                <tr>
                  <td>{{ $doctor->nama }}</td>
                  <td>{{ $doctor->spesialis }}</td>
                  <td>{{ $doctor->institution }}</td>
                  <td>{{ $doctor->username }}</td>
                  <td>{{ $doctor->user->email ?? '-' }}</td>
                  <td>
                    <center>
                      {{-- Tombol Edit --}}
                      <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editDoctorModal{{ $doctor->id }}">
                        <i class="fas fa-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Dokter"></i>
                      </button>

                      {{-- Tombol Hapus --}}
                      <button 
                        class="btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteDoctor{{ $doctor->id }}">
                        <i class="fas fa-trash-alt" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Dokter"></i>
                      </button>
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
@endsection

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
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t));
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
  .tombol-add {
    background-color: #175e54 !important;
  }

  table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
    text-align: left;
    border-top-width: 1px;
    background: #175e54 !important;
    color: white !important;
  }
</style>
