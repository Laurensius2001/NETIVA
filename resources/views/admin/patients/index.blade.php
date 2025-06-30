@extends('layouts.admin')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <style>
    .dataTables_filter input {
      max-width: 300px;
    }
    .tombol-add {
      background-color: #175e54 !important;
    }

    table.dataTable thead th, table.dataTable thead td, table.dataTable tfoot th, table.dataTable tfoot td {
      border-top-width: 1px;
      background: #175e54 !important;
      color: white !important;
    }

  </style>
@section('content')

  <div class="container py-4">
  <span class="badge rounded-pill px-4 py-2 mb-4 shadow-lg" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
    <i class="fa-solid fa-users-gear"></i> Halaman Kelolah Pasien
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
        <button class="btn mb-3 fw-bold tombol-add text-white" data-bs-toggle="modal" data-bs-target="#createPatientModal">
          <i class="bi bi-plus-circle me-1"></i> Tambah Pasien
        </button>
        <div class="table-responsive">
          <table class="table" id="patientsTable">
            <thead>
              <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Dokter Penanggung Jawab</th>
                <th>Status Verifikasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($patients as $patient)
                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="deletePatientModal{{ $patient->id }}" tabindex="-1" aria-labelledby="deletePatientModalLabel{{ $patient->id }}" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger">
                      <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deletePatientModalLabel{{ $patient->id }}">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                      </div>
                      <div class="modal-body">
                        Yakin ingin menghapus pasien <strong>{{ $patient->nama }}</strong>?
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="d-inline">
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
                  <td>{{ $patient->nik }}</td>
                  <td>{{ $patient->nama }}</td>
                  <td>
                    <center>
                      <span class="badge bg-success">{{ $patient->umur }}</span>
                    </center>
                  </td>
                  <td>{{ $patient->doctor->nama ?? '-' }}</td>
                  <td>
                    <span class="badge 
                      @if ($patient->verifikasi === 'positif') bg-danger 
                      @elseif($patient->verifikasi === 'negatif') bg-success 
                      @else bg-secondary 
                      @endif">
                      {{ ucfirst($patient->verifikasi ?? 'belum diverifikasi') }}
                    </span>
                  </td>
                  <td>
                    <center>
                     {{-- Detail --}}
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $patient->id }}">
                          <i class="fas fa-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"></i>
                        </button>

                        {{-- Edit --}}
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">
                          <i class="fas fa-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Pasien"></i>
                        </button>

                        {{-- Hapus --}}
                        <button 
                            class="btn btn-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deletePatientModal{{ $patient->id }}">
                            <i class="fas fa-trash-alt" title="Hapus Pasien"></i>
                        </button>
                    </center>
                  </td>

                </tr>

              <div class="modal fade" id="detailModal{{ $patient->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $patient->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #175e54; font-size:22px; color: white; font-weight: 700;">
                        <h5 class="modal-title" id="detailModalLabel{{ $patient->id }}">Detail Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group shadow-sm rounded">
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-id-card fa-fw me-3 text-primary"></i>
                            <strong class="me-2">NIK:</strong> {{ $patient->nik }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-user fa-fw me-3 text-success"></i>
                            <strong class="me-2">Nama:</strong> {{ $patient->nama }}
                          </li>
                          <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div>
                              <i class="fa-solid fa-cake-candles fa-fw me-3 text-warning"></i>
                              <strong>Umur:</strong> <span class="badge bg-success rounded-pill">{{ $patient->umur }} th</span>
                            </div>
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-map-marker-alt fa-fw me-3 text-danger"></i>
                            <strong class="me-2">Tempat Lahir:</strong> {{ $patient->tempat_lahir }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-calendar-days fa-fw me-3 text-info"></i>
                            <strong class="me-2">Tanggal Lahir:</strong> {{ $patient->tanggal_lahir }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-flag fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">Provinsi:</strong> {{ $patient->provinsi }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-city fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">Kabupaten:</strong> {{ $patient->kabupaten }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-map fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">Kecamatan:</strong> {{ $patient->kecamatan }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-house fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">Kelurahan:</strong> {{ $patient->kelurahan }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-location-dot fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">RT/RW:</strong> {{ $patient->rt_rw }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-road fa-fw me-3 text-secondary"></i>
                            <strong class="me-2">Alamat:</strong> {{ $patient->alamat }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-briefcase fa-fw me-3 text-primary"></i>
                            <strong class="me-2">Pekerjaan:</strong> {{ $patient->pekerjaan }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-tint fa-fw me-3 text-danger"></i>
                            <strong class="me-2">Golongan Darah:</strong> {{ $patient->golongan_darah }}
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-user-doctor fa-fw me-3 text-info"></i>
                            <strong class="me-2">Dokter Penanggung Jawab:</strong> {{ $patient->doctor->nama ?? '-' }}
                          </li>
                          <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div>
                              <i class="fa-solid fa-check-circle fa-fw me-3 text-secondary"></i>
                              <strong >Status Verifikasi:</strong>
                              <span class="badge 
                                @if ($patient->verifikasi === 'positif') bg-danger 
                                @elseif($patient->verifikasi === 'negatif') bg-success 
                                @else bg-secondary 
                                @endif
                                rounded-pill px-3 py-2">
                                {{ ucfirst($patient->verifikasi ?? 'belum diverifikasi') }}
                              </span>
                            </div>
                          </li>
                          <li class="list-group-item d-flex align-items-center">
                            <i class="fa-solid fa-comments fa-fw me-3 text-warning"></i>
                            <strong class="me-2">Ket. Dokter:</strong>
                            @if($patient->komentar)
                              {{ $patient->komentar }}
                            @else
                              <span class="badge bg-warning text-dark">Belum dikomentari</span>
                            @endif
                          </li>
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    
              {{-- Modal Edit --}}
        <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $patient->nama }}" required>
                      </div>
                      <div class="col-md-6">
                        <label>Umur</label>
                        <input type="number" name="umur" class="form-control" value="{{ $patient->umur }}" required>
                      </div>
                      <div class="col-md-6">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ $patient->tempat_lahir }}" required>
                      </div>
                      <div class="col-md-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $patient->tanggal_lahir }}" required>
                      </div>
                      <div class="col-md-6">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $patient->alamat }}" required>
                      </div>
                      <div class="col-md-6">
                        <label>Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" value="{{ $patient->provinsi }}">
                      </div>
                      <div class="col-md-6">
                        <label>Kabupaten</label>
                        <input type="text" name="kabupaten" class="form-control" value="{{ $patient->kabupaten }}">
                      </div>
                      <div class="col-md-6">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ $patient->kecamatan }}">
                      </div>
                      <div class="col-md-6">
                        <label>Kelurahan</label>
                        <input type="text" name="kelurahan" class="form-control" value="{{ $patient->kelurahan }}">
                      </div>
                      <div class="col-md-6">
                        <label>RT/RW</label>
                        <input type="text" name="rt_rw" class="form-control" value="{{ $patient->rt_rw }}" placeholder="004/009">
                      </div>
                      <div class="col-md-6">
                        <label>Dokter Penanggung Jawab</label>
                        <select name="doctor_id" class="form-select">
                          <option value="">Pilih Dokter</option>
                          @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $patient->doctor_id == $doctor->id ? 'selected' : '' }}>
                              {{ $doctor->nama }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ $patient->pekerjaan }}">
                      </div>
                      <div class="col-md-6">
                        <label>Golongan Darah</label>
                        <select name="golongan_darah" class="form-select" required>
                          <option value="">Pilih</option>
                          <option value="A" {{ $patient->golongan_darah === 'A' ? 'selected' : '' }}>A</option>
                          <option value="B" {{ $patient->golongan_darah === 'B' ? 'selected' : '' }}>B</option>
                          <option value="AB" {{ $patient->golongan_darah === 'AB' ? 'selected' : '' }}>AB</option>
                          <option value="O" {{ $patient->golongan_darah === 'O' ? 'selected' : '' }}>O</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
            @empty
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>





  <div class="modal fade" id="createPatientModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('admin.patients.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pasien</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Nama</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label for="nik">NIK</label>
              <input type="text" name="nik" id="nik" class="form-control" required>
            </div>

          <div class="col-md-6">
            <label for="kota">Provinsi</label>
            <select name="provinsi" id="provinsi" class="form-select" required>
              <option value="">-- Pilih Provinsi --</option>
              @foreach ($provinces as $provinsi)
                <option value="{{ $provinsi['id'] }}" {{ $provinsi['id'] == '32' ? 'selected' : '' }}>
              {{ $provinsi['name'] }}
            </option>
              @endforeach
            </select>
          </div>


            <div class="col-md-6">
              <label for="kota">Kota</label>
              <select name="kabupaten" id="kota" class="form-select" required>
                <option value="">Pilih Kota</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="kecamatan">Kecamatan</label>
              <select name="kecamatan" id="kecamatan" class="form-select" required>
                <option value="">Pilih Kecamatan</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="kelurahan">Kelurahan</label>
              <select name="kelurahan" id="kelurahan" class="form-select" required>
                <option value="">Pilih Kelurahan</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>RT/RW</label>
              <input type="text" name="rt_rw" class="form-control" placeholder="004/009" required>
            </div>
            <div class="col-md-6">
              <label>Alamat</label>
              <input type="text" name="alamat" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Umur</label>
              <input type="number" name="umur" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Tempat Lahir</label>
              <input type="text" name="tempat_lahir" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Pekerjaan</label>
              <input type="text" name="pekerjaan" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Golongan Darah</label>
              <select name="golongan_darah" class="form-select" required>
                <option value="">Pilih</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="doctor_id">Dokter</label>
              <select name="doctor_id" class="form-select" required>
                <option value="">Pilih Dokter</option>
                @foreach ($doctors as $doctor)
                  <option value="{{ $doctor->id }}">{{ $doctor->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>


  
@endsection
   <script>
      document.addEventListener('DOMContentLoaded', function () {
        const id = sel => document.getElementById(sel);

        const resetSelect = (id, label) => {
          const el = document.getElementById(id);
          if (el) el.innerHTML = `<option value="">Pilih ${label}</option>`;
        };

        const show = sel => {
          const el = document.getElementById(sel + '-container');
          if (el) el.classList.remove('d-none');
        };

        const hide = sel => {
          const el = document.getElementById(sel + '-container');
          if (el) el.classList.add('d-none');
        };

        const prov = id('provinsi');
        const kota = id('kota');
        console.log(kota)
        const kecamatan = id('kecamatan');
        const kelurahan = id('kelurahan');

        // Fetch Provinsi saat halaman ready
        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
          .then(res => res.json())
          .then(data => {
            prov.innerHTML = `<option value="">-- Pilih Provinsi --</option>`;
            data.forEach(p => {
              const selected = p.id === '32' ? 'selected' : '';
              prov.innerHTML += `<option value="${p.id}" ${selected}>${p.name}</option>`;
            });

            // Setelah provinsi sukses, trigger load kota
            prov.dispatchEvent(new Event('change'));
          });

        prov.addEventListener('change', () => {
          const provId = prov.value;
          resetSelect('kota', 'Kota');
          resetSelect('kecamatan', 'Kecamatan');
          resetSelect('kelurahan', 'Kelurahan');
          if (!provId) return;

          fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
            .then(res => res.json())
            .then(data => {
              data.forEach(d => kota.innerHTML += `<option value="${d.id}">${d.name}</option>`);
              console.log(data)
            });
        });

        kota.addEventListener('change', () => {
          const kotaId = kota.value;
          resetSelect('kecamatan', 'Kecamatan');
          resetSelect('kelurahan', 'Kelurahan');
          if (!kotaId) return;

          fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaId}.json`)
            .then(res => res.json())
            .then(data => {
              data.forEach(k => kecamatan.innerHTML += `<option value="${k.id}">${k.name}</option>`);
            });
        });

        kecamatan.addEventListener('change', () => {
          const kecId = kecamatan.value;
          resetSelect('kelurahan', 'Kelurahan');
          if (!kecId) return;

          fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecId}.json`)
            .then(res => res.json())
            .then(data => {
              data.forEach(d => kelurahan.innerHTML += `<option value="${d.id}">${d.name}</option>`);
            });
        });

        // Auto-trigger fetch provinsi saat modal tambah dibuka
        document.getElementById('createPatientModal')?.addEventListener('shown.bs.modal', () => {
          if (prov.options.length <= 1) {
            fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
              .then(res => res.json())
              .then(data => {
                prov.innerHTML = `<option value="">-- Pilih Provinsi --</option>`;
                data.forEach(p => {
                  const selected = p.id === '32' ? 'selected' : '';
                  prov.innerHTML += `<option value="${p.id}" ${selected}>${p.name}</option>`;
                });
                prov.dispatchEvent(new Event('change'));
              });
          }
        });
      });
    </script>
  
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
  $(document).ready(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltipTriggerList.forEach(t => new bootstrap.Tooltip(t))
    if (!$.fn.DataTable.isDataTable('#patientsTable')) {
      $('#patientsTable').DataTable({
          responsive: true,
          pageLength: 10, // tampilkan 10 data per halaman
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
    }
  });
  </script>

