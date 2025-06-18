<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<x-layout>
<div class="container">
  @if(session('success'))
    <script>
      iziToast.success({
        title: 'Sukses',
        message: '{{ session('success') }}',
        position: 'topCenter'
      });
    </script>
  @endif

  <div class="row">
    <div class="col-md-4">
    <button type="button" class="btn btn-primary position-relative mb-4">
      Selamat datang, Dr. {{ $doctor->nama }}
      <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
        <span class="visually-hidden">New alerts</span>
      </span>
    </button>
      <div class="card mb-4">
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item active">Detail Dokter</li>
            <li class="list-group-item"><strong>Nama:</strong> {{ $doctor->nama }}</li>
            <li class="list-group-item"><strong>Email:</strong> {{ $doctor->user->email }}</li>
            <li class="list-group-item"><strong>Spesialisasi:</strong> {{ $doctor->spesialis }}</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-md-8">
    <button type="button" class="btn btn-primary position-relative mb-4">
      Data Pasien
      <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
        <span class="visually-hidden">New alerts</span>
      </span>
    </button>
      <div class="px-4 py-4 bg-light rounded-3">
        <div class="container-fluid">
          <div class="table-responsive">
            <table class="table table-bordered" id="table-pasien">
              <thead class="table-dark">
                <tr>
                  <th>Nama</th>
                  <th>Citra Sebelum</th>
                  <th>Citra Sesudah</th>
                  <th>Komentar</th>
                  <th>Verifikasi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($patients as $patient)
                <tr>
                  <td>{{ $patient->nama }}</td>
                  <td class="text-center">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#gambarSebelumModal{{ $patient->id }}">
                      Lihat Gambar
                    </button>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#gambarSesudahModal{{ $patient->id }}">
                      Lihat Gambar
                    </button>
                  </td>
                  <td>
                    @if($patient->komentar)
                      {{ $patient->komentar }}
                    @else
                      <span class="badge bg-warning text-dark">Belum dikomentari</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="badge 
                      @if ($patient->verifikasi === 'positif') bg-danger 
                      @elseif($patient->verifikasi === 'negatif') bg-success 
                      @else bg-secondary 
                      @endif">
                      {{ ucfirst($patient->verifikasi ?? 'belum diverifikasi') }}
                    </span>
                  </td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $patient->id }}">
                      Edit
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      @foreach ($patients as $patient)
        <!-- Modal Gambar Sebelum -->
        <div class="modal fade" id="gambarSebelumModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gambar Sebelum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                @if ($patient->citra)
                  <img src="{{ asset('storage/' . $patient->citra->citra_sebelum) }}" class="img-fluid">
                @else
                  <p class="text-muted">Belum ada gambar.</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Gambar Sesudah -->
        <div class="modal fade" id="gambarSesudahModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Gambar Sesudah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body text-center">
                @if ($patient->citra)
                  <img src="{{ asset('storage/' . $patient->citra->citra_sesudah) }}" class="img-fluid">
                @else
                  <p class="text-muted">Belum ada gambar.</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="modalUpdate{{ $patient->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $patient->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{ route('dokter.update.komentar.verifikasi', $patient->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel{{ $patient->id }}">Ubah Komentar & Verifikasi</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="komentar" class="form-label">Komentar</label>
                    <textarea name="komentar" class="form-control" rows="3">{{ old('komentar', $patient->komentar) }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label for="verifikasi" class="form-label">Verifikasi</label>
                    <select name="verifikasi" class="form-select">
                      <option value="">-- Pilih Status --</option>
                      <option value="positif" {{ $patient->verifikasi === 'positif' ? 'selected' : '' }}>Positif</option>
                      <option value="negatif" {{ $patient->verifikasi === 'negatif' ? 'selected' : '' }}>Negatif</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('#table-pasien').DataTable({
      paging: false,
      info: false,
      responsive: true,
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
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
