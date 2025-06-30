@extends('layouts.admin')
<style>
  .modal {
    z-index: 1070 !important;
  }
  .tombol-add {
    background-color: #175e54 !important;
  }
  table.table-bordered.dataTable thead tr:first-child th, table.table-bordered.dataTable thead tr:first-child td {
    border-top-width: 1px;
    background: #175e54 !important;
    color: white !important;
  }
</style>
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="container  py-4">
  <span class="badge rounded-pill px-4 py-2 mb-4 shadow-lg" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
    <i class="fa-solid fa-images"></i> Halaman Kelolah Citra Pasien
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
      <button class="btn mb-3 fw-bold tombol-add text-white" data-bs-toggle="modal" data-bs-target="#createCitraModal">
      <i class="bi bi-plus-circle me-1"></i> Tambah Citra Pasien
    </button>
      <div class="table-responsive">
        <table id="table-citra" class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>Nama Pasien</th>
              <th>Status Verifikasi</th>
              <th>Ket.Dokter</th>
              <th>Ket.Nakes</th>
              <th>Gambar Sebelum</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($citras as $index => $citra)
              <div class="modal fade" id="confirmDeleteModal{{ $citra->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content border-danger">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title">Konfirmasi Hapus</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                      Apakah Anda yakin ingin menghapus citra <strong>{{ $citra->patient->nama ?? '-' }}</<strong>?
                    </div>
                    <div class="modal-footer">
                      <form action="{{ route('admin.citra.destroy', $citra->id) }}" method="POST" class="d-inline">
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
                <td>{{ $citra->patient->nama ?? '-' }}</td>
                <td>
                  <center>
                    <span class="badge 
                      @if ($citra->patient->verifikasi === 'positif') bg-danger 
                      @elseif($citra->patient->verifikasi === 'negatif') bg-info text-dark 
                      @else bg-secondary 
                      @endif">
                      {{ ucfirst($citra->patient->verifikasi ?? 'belum diverifikasi') }}
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
                <td>{{ $citra->ket_nakes ?? '-' }}</td>
                <td>
                  <center>
                    <div class="btn-group" role="group" aria-label="Gambar Citra">
                      <button type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalSebelum{{ $citra->id }}"
                        title="Lihat Gambar Sebelum">
                        <i class="bi bi-image-fill"></i>
                      </button>

                      <button type="button"
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#modalSesudah{{ $citra->id }}"
                        title="Lihat Gambar Sesudah">
                        <i class="bi bi-image-alt"></i>
                      </button>

                      <button type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCitraAi{{ $citra->id }}"
                        title="Lihat Gambar Citra AI">
                        <i class="bi bi-cpu"></i>
                      </button>
                    </div>
                  </center>
                </td>
                <td>
                  <center>
                    <button 
                      class="btn btn-sm btn-warning" 
                      data-bs-toggle="modal" 
                      data-bs-target="#editCitraModal{{ $citra->id }}" 
                      data-bs-toggle="tooltip" 
                      data-bs-placement="top" 
                      title="Edit Citra">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button 
                      type="button" 
                      class="btn btn-sm btn-danger" 
                      data-bs-toggle="modal" 
                      data-bs-target="#confirmDeleteModal{{ $citra->id }}" 
                      title="Hapus Citra">
                      <i class="fas fa-trash-alt"></i>
                    </button>
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
                <h5 class="modal-title">Gambar Sebelum -{{ $citra->patient->nama }}</h5>
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
                <h5 class="modal-title">Gambar Sesudah - {{ $citra->patient->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $citra->citra_sesudah) }}" class="img-fluid rounded">
              </div>
            </div>
          </div>
        </div>

        <!-- Modal untuk Citra AI -->
        <div class="modal fade" id="modalCitraAi{{ $citra->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Citra AI - {{ $citra->patient->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                <img src="{{ asset('storage/' . $citra->citra_ai) }}" class="img-fluid rounded" alt="Citra AI">
              </div>
            </div>
          </div>
        </div>


        <!-- Modal Edit -->
        <div class="modal fade" id="editCitraModal{{ $citra->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <form action="{{ route('admin.citra.update', $citra->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Citra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                {{-- Pasien --}}
                <div class="mb-3">
                  <label class="form-label">Pasien</label>
                  <select class="form-select" name="patient_id" required>
                    @foreach($patients as $patient)
                      <option value="{{ $patient->id }}" {{ $patient->id == $citra->patient_id ? 'selected' : '' }}>{{ $patient->nama }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="row">
                  {{-- Gambar Sebelum --}}
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Gambar Sebelum</label><br>
                    <img src="{{ asset('storage/' . $citra->citra_sebelum) }}" alt="Sebelum" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <div class="input-group">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Pilih Gambar
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editSebelum{{ $citra->id }}', true)">Ambil dari Kamera</a></li>
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editSebelum{{ $citra->id }}', false)">Pilih dari Galeri</a></li>
                      </ul>
                      <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameEditSebelum{{ $citra->id }}" readonly>
                    </div>
                    <input type="file" class="d-none" name="sebelum" id="editSebelum{{ $citra->id }}" accept="image/*" onchange="showFileName(this, 'fileNameEditSebelum{{ $citra->id }}')">
                  </div>

                  {{-- Gambar Sesudah --}}
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Gambar Sesudah</label><br>
                    <img src="{{ asset('storage/' . $citra->citra_sesudah) }}" alt="Sesudah" class="img-thumbnail mb-2" style="max-height: 150px;">
                    <div class="input-group">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Pilih Gambar
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editSesudah{{ $citra->id }}', true)">Ambil dari Kamera</a></li>
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editSesudah{{ $citra->id }}', false)">Pilih dari Galeri</a></li>
                      </ul>
                      <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameEditSesudah{{ $citra->id }}" readonly>
                    </div>
                    <input type="file" class="d-none" name="sesudah" id="editSesudah{{ $citra->id }}" accept="image/*" onchange="showFileName(this, 'fileNameEditSesudah{{ $citra->id }}')">
                  </div>

                  {{-- Gambar Citra AI --}}
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Gambar Citra AI</label><br>
                    @if($citra->citra_ai)
                      <img src="{{ asset('storage/' . $citra->citra_ai) }}" alt="AI" class="img-thumbnail mb-2" style="max-height: 150px;">
                    @endif
                    <div class="input-group">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Pilih Gambar
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editAi{{ $citra->id }}', true)">Ambil dari Kamera</a></li>
                        <li><a class="dropdown-item" href="#" onclick="triggerInput('editAi{{ $citra->id }}', false)">Pilih dari Galeri</a></li>
                      </ul>
                      <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameEditAi{{ $citra->id }}" readonly>
                    </div>
                    <input type="file" class="d-none" name="citra_ai" id="editAi{{ $citra->id }}" accept="image/*" onchange="showFileName(this, 'fileNameEditAi{{ $citra->id }}')">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Keterangan Nakes</label>
                    <textarea name="ket_nakes" class="form-control" rows="3" placeholder="Isi keterangan...">{{ $citra->ket_nakes }}</textarea>
                  </div>
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
          <center>
            <span class="badge bg-warning text-dark mb-2"><small class="text-muted">Ukuran maksimal gambar: <strong>10 MB</strong>. Gunakan format JPG, PNG, atau JPEG.</small></span>
          </center>
          
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
            <label class="form-label">Gambar Sebelum</label>
            <div class="input-group">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Pilih Gambar
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="triggerInput('sebelum', true)">Ambil dari Kamera</a></li>
                <li><a class="dropdown-item" href="#" onclick="triggerInput('sebelum', false)">Pilih dari Galeri</a></li>
              </ul>
              <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameSebelum" readonly>
            </div>
            <input type="file" name="sebelum" id="sebelum" accept="image/*" class="d-none" required onchange="showFileName(this, 'fileNameSebelum')">
          </div>

          <div class="mb-3">
            <label class="form-label">Gambar Sesudah</label>
            <div class="input-group">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Pilih Gambar
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="triggerInput('sesudah', true)">Ambil dari Kamera</a></li>
                <li><a class="dropdown-item" href="#" onclick="triggerInput('sesudah', false)">Pilih dari Galeri</a></li>
              </ul>
              <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameSesudah" readonly>
            </div>
            <input type="file" name="sesudah" id="sesudah" accept="image/*" class="d-none" required onchange="showFileName(this, 'fileNameSesudah')">
          </div>

          <div class="mb-3">
            <label class="form-label">Citra AI</label>
            <div class="input-group">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Pilih Gambar
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="triggerInput('citra_ai', true)">Ambil dari Kamera</a></li>
                <li><a class="dropdown-item" href="#" onclick="triggerInput('citra_ai', false)">Pilih dari Galeri</a></li>
              </ul>
              <input type="text" class="form-control" placeholder="Belum ada file" id="fileNameAI" readonly>
            </div>
            <input type="file" name="citra_ai" id="citra_ai" accept="image/*" class="d-none" onchange="showFileName(this, 'fileNameAI')">
          </div>

          <div class="mb-3">
            <label class="form-label">Keterangan Nakes</label>
            <textarea class="form-control" name="ket_nakes" rows="3" placeholder="Isi keterangan..."></textarea>
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
    document.addEventListener('DOMContentLoaded', function () {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
      tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el)
      })
    });

    function triggerInput(id, isCamera) {
    const input = document.getElementById(id);
    input.removeAttribute('capture');
    if (isCamera) {
      input.setAttribute('capture', 'environment');
    }
    input.click();
  }

  function showFileName(input, targetId) {
    const file = input.files[0];
    if (file) {
      document.getElementById(targetId).value = file.name;
    }
  }

  $(document).ready(function () {
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
@endsection