<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<x-layout>
<div class="container">
  <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
      <div class="breadcrumb-container">
      <div class="breadcrumb-container mb-2">
      <a href="/admin/dashboard" class="breadcrumb-item first">
        <i class="bi bi-house-fill"></i>
      </a>
      <a href="#" class="breadcrumb-item">
        <i class="bi bi-beaker"></i> Kelola Citra Pasien
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
      <button class="btn btn-primary mb-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createCitraModal">Tambah Citra Pasien</button>
      <div class="table-responsive">
        <table id="table-citra" class="table table-bordered table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>Nama Pasien</th>
              <th>Status</th>
              <th>Komentar</th>
              <th>Gambar Sebelum</th>
              <th>Gambar Sesudah</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($citras as $index => $citra)
              <tr>
                <td>{{ $citra->patient->nama ?? '-' }}</td>
                <td>
                  <center>
                    <span class="badge 
                      @if ($citra->patient->verifikasi === 'positif') bg-danger 
                      @elseif($citra->patient->verifikasi === 'negatif') bg-info text-dark 
                      @else bg-secondary 
                      @endif">
                      {{ ucfirst($citra->patient->verifikasi ?? '-') }}
                    </span>
                  </center>
                </td>
                <td>
                  @if($citra->patient->komentar)
                    {{ $citra->patient->komentar }}
                  @else
                    <span class="badge bg-warning text-dark">Belum dikomentari</span>
                  @endif
                </td>
                <td>
                  <center>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalSebelum{{ $citra->id }}">Lihat Gambar</button>
                  </center>
                </td>
                <td>
                  <center>
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalSesudah{{ $citra->id }}">Lihat Gambar</button>
                  </center>
                </td>
                <td>
                  <center>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCitraModal{{ $citra->id }}">Edit</button>
                    <form action="{{ route('admin.citra.destroy', $citra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
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

        @foreach ($citras as $citra)
        <!-- Modal Gambar Sebelum -->
        <div class="modal fade" id="modalSebelum{{ $citra->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gambar Sebelum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $citra->citra_sebelum) }}" class="img-fluid rounded">
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Gambar Sesudah -->
        <div class="modal fade" id="modalSesudah{{ $citra->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gambar Sesudah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $citra->citra_sesudah) }}" class="img-fluid rounded">
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editCitraModal{{ $citra->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{ route('admin.citra.update', $citra->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Citra</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Pasien</label>
                    <select class="form-select" name="patient_id" required>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ $patient->id == $citra->patient_id ? 'selected' : '' }}>{{ $patient->nama }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Gambar Sebelum</label><br>
                    <img src="{{ asset('storage/' . $citra->citra_sebelum) }}" alt="Sebelum" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <input type="file" class="form-control" name="sebelum" accept="image/*">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Gambar Sesudah</label><br>
                    <img src="{{ asset('storage/' . $citra->citra_sesudah) }}" alt="Sesudah" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <input type="file" class="form-control" name="sesudah" accept="image/*">
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-primary" type="submit">Update</button>
                  <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      @endforeach
      </div>
    </div>
  </div>
</div>



              

<!-- Modal Tambah -->
<div class="modal fade" id="createCitraModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('admin.citra.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Citra Pasien</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pasien</label>
            <select class="form-select" name="patient_id" required>
              <option value="">-- Pilih Pasien --</option>
              @foreach($patients as $patient)
                <option value="{{ $patient->id }}">{{ $patient->nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
          <label>Gambar Sebelum</label>
            <input type="file" name="gambar_sebelum" class="form-control" accept="image/*" capture="environment" required>
          </div>
          <div class="mb-3">
            <label>Gambar Sesudah</label>
            <input type="file" name="gambar_sesudah" class="form-control" accept="image/*" capture="environment" required>
          </div>

        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Simpan</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  $(document).ready(function () {
    console.log('DataTables Loaded');
    $('#table-citra').DataTable({
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
</x-layout>
