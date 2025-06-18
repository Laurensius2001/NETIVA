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
        <i class="bi bi-beaker"></i> Kelola Pasien
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
        <button type="button" class="btn btn-primary mb-3  fw-bold" data-bs-toggle="modal" data-bs-target="#createPatientModal">
          Tambah Pasien
        </button>
        <div class="table-responsive">
          <table class="table" id="patientsTable">
            <thead class="table-dark">
              <tr>
                <th>NIK</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Verifikasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($patients as $patient)
              <tr>
                <td>{{ $patient->nik }}</td>
                <td>{{ $patient->nama }}</td>
                <td>{{ $patient->user->username ?? '-' }}</td>
                <td>{{ $patient->user->email ?? '-' }}</td>
                <td>{{ $patient->tempat_lahir }}</td>
                <td>{{ $patient->tanggal_lahir }}</td>
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
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $patient->id }}">
                      Detail
                  </button>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPatientModal{{ $patient->id }}">Edit</button>
                  <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pasien ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </td>
              </tr>

              <div class="modal fade" id="detailModal{{ $patient->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $patient->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="detailModalLabel{{ $patient->id }}">Detail Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                          <li class="list-group-item"><strong>NIK:</strong> {{ $patient->nik }}</li>
                            <li class="list-group-item"><strong>Nama:</strong> {{ $patient->nama }}</li>
                            <li class="list-group-item"><strong>Umur:</strong> {{ $patient->umur }} tahun</li>
                            <li class="list-group-item"><strong>Tempat Lahir:</strong> {{ $patient->tempat_lahir }}</li>
                            <li class="list-group-item"><strong>Tanggal Lahir:</strong> {{ $patient->tanggal_lahir }}</li>
                            <li class="list-group-item"><strong>Provinsi:</strong> {{ $patient->provinsi }}</li>
                            <li class="list-group-item"><strong>Kabupaten:</strong> {{ $patient->kabupaten }}</li>
                            <li class="list-group-item"><strong>Kecamatan:</strong> {{ $patient->kecamatan }}</li>
                            <li class="list-group-item"><strong>Kelurahan:</strong> {{ $patient->kelurahan }}</li>
                            <li class="list-group-item"><strong>RT/RW:</strong> {{ $patient->rt_rw }}</li>
                            <li class="list-group-item"><strong>Alamat:</strong> {{ $patient->alamat }}</li>
                            <li class="list-group-item"><strong>Pekerjaan:</strong> {{ $patient->pekerjaan }}</li>
                            <li class="list-group-item"><strong>Golongan Darah:</strong> {{ $patient->golongan_darah }}</li>
                            <li class="list-group-item"><strong>Email:</strong> {{ $patient->user->email ?? '-' }}</li>
                            <li class="list-group-item">
                              <strong>Status Verifikasi:</strong>
                              <span class="badge 
                                @if ($patient->verifikasi === 'positif') bg-danger 
                                @elseif($patient->verifikasi === 'negatif') bg-success 
                                @else bg-secondary 
                                @endif">
                                {{ ucfirst($patient->verifikasi ?? 'belum diverifikasi') }}
                              </span>
                            </li>
                            <li class="list-group-item">
                            <strong>Komentar:</strong>
                              @if($patient->komentar)
                                {{ $patient->komentar }}
                              @else
                                <span class="badge bg-warning text-dark">Belum dikomentari</span>
                              @endif
                            </li>
                            <li class="list-group-item"><strong>Username:</strong> {{ $patient->user->username ?? '-' }}</li>
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
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $patient->user->email }}" required>
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
                      <div class="col-md-6">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $patient->user->username }}" required>
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
              <label for="provinsi">Provinsi</label>
              <select name="provinsi" id="provinsi" class="form-select" required>
                <option value="">Pilih Provinsi</option>
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
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Username</label>
              <input type="text" name="username" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
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

  
</x-layout>
   <script>
      const id = sel => document.getElementById(sel);
      const show = sel => id(sel + '-container')?.classList.remove('d-none');
      const hide = sel => id(sel + '-container')?.classList.add('d-none');

      const resetSelect = (id, label) => {
        const el = document.getElementById(id);
        el.innerHTML = `<option value="">Pilih ${label}</option>`;
      };

      const initWilayahDropdowns = () => {
        resetSelect('provinsi', 'Provinsi');
        resetSelect('kota', 'Kota');
        resetSelect('kecamatan', 'Kecamatan');
        resetSelect('kelurahan', 'Kelurahan');
        hide('kode_pos'); // karena tidak tersedia
        fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
          .then(res => res.json())
          .then(data => {
            const prov = id('provinsi');
            data.forEach(p => prov.innerHTML += `<option value="${p.id}">${p.name}</option>`);
            show('provinsi');
          })
          .catch(error => {
            console.error('Gagal ambil provinsi:', error);
            alert('Gagal memuat data provinsi.');
          });
      };

      document.getElementById('createPatientModal').addEventListener('shown.bs.modal', () => {
        initWilayahDropdowns();
      });

      id('provinsi')?.addEventListener('change', () => {
        const provId = id('provinsi').value;
        resetSelect('kota', 'Kota');
        resetSelect('kecamatan', 'Kecamatan');
        resetSelect('kelurahan', 'Kelurahan');
        if (!provId) return;
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provId}.json`)
          .then(res => res.json())
          .then(data => {
            const kota = id('kota');
            data.forEach(k => kota.innerHTML += `<option value="${k.id}">${k.name}</option>`);
            show('kota');
          })
          .catch(error => {
            console.error('Gagal ambil kota:', error);
            alert('Gagal memuat data kota.');
          });
      });

      id('kota')?.addEventListener('change', () => {
        const kotaId = id('kota').value;
        resetSelect('kecamatan', 'Kecamatan');
        resetSelect('kelurahan', 'Kelurahan');
        if (!kotaId) return;
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kotaId}.json`)
          .then(res => res.json())
          .then(data => {
            const kec = id('kecamatan');
            data.forEach(k => kec.innerHTML += `<option value="${k.id}">${k.name}</option>`);
            show('kecamatan');
          })
          .catch(error => {
            console.error('Gagal ambil kecamatan:', error);
            alert('Gagal memuat data kecamatan.');
          });
      });

      id('kecamatan')?.addEventListener('change', () => {
        const kecId = id('kecamatan').value;
        resetSelect('kelurahan', 'Kelurahan');
        if (!kecId) return;
        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecId}.json`)
          .then(res => res.json())
          .then(data => {
            const kel = id('kelurahan');
            data.forEach(d => kel.innerHTML += `<option value="${d.id}">${d.name}</option>`);
            show('kelurahan');
          })
          .catch(error => {
            console.error('Gagal ambil kelurahan:', error);
            alert('Gagal memuat data kelurahan.');
          });
      });
    </script>
  
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
  $(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#patientsTable')) {
      $('#patientsTable').DataTable({
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
    }
  });
  </script>

  <style>
    .dataTables_filter input {
      max-width: 300px;
    }
  </style>
