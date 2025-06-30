<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>

@extends('layouts.admin')
<style>
  table.table-bordered.dataTable thead tr:first-child th, table.table-bordered.dataTable thead tr:first-child td {
    border-top-width: 1px;
    background: #175e54 !important;
    color: white !important;
  }
</style>
@section('content')
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

  <div class="col-md-8">
  <button type="button" class="btn position-relative mb-4" style="background-color: #175e54; font-size:22px; color: white; font-weight: 700;">
    <i class="fa-solid fa-user-nurse"></i> Selamat datang, Dr. {{ $doctor->nama }}
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
      <span class="visually-hidden">New alerts</span>
    </span>
  </button>
    <div class="card mb-4 shadow-lg" style="background-color: #f6fbf7;">
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item" style="background-color: #175e54; color: white; font-weight: 700;">Detail Dokter</li>
          <li class="list-group-item"><i class="fa-solid fa-user"></i><strong>  Nama:</strong> {{ $doctor->nama }}</li>
          <li class="list-group-item"><i class="fa-solid fa-envelope-open-text"></i> <strong>Email:</strong> {{ $doctor->user->email }}</li>
          <li class="list-group-item"><i class="fa-solid fa-sort"></i> <strong>Spesialisasi:</strong> {{ $doctor->spesialis }}</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-md-12">
  <button type="button" class="btn position-relative mb-4" style="background-color: #175e54; font-size:22px; color: white; font-weight: 700;">
    <i class="fa-solid fa-users-gear"></i> Data Pasien
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
      <span class="visually-hidden">New alerts</span>
    </span>
  </button>
    <div class="px-4 py-4 rounded-3 shadow-lg" style="background-color: #eef8f0;">
      <div class="container-fluid">
        <div class="table-responsive">
          <table class="table table-bordered" id="table-pasien">
            <thead class="table-dark">
              <tr>
                <th>Nama</th>
                <th>Citra Sebelum</th>
                <th>Ket.Dokter</th>
                <th>Ket.Nakes</th>
                <th>Verifikasi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($patients as $patient)
              <tr>
                <td>{{ $patient->nama }}</td>
                <td class="text-center">
                  <div class="btn-group" role="group" aria-label="Lihat Gambar Citra">
                    <button type="button"
                      class="btn btn-danger btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#gambarSebelumModal{{ $patient->id }}"
                      title="Lihat Gambar Sebelum">
                      <i class="bi bi-image-fill"></i>
                    </button>

                    <button type="button"
                      class="btn btn-warning btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#gambarSesudahModal{{ $patient->id }}"
                      title="Lihat Gambar Sesudah">
                      <i class="bi bi-image-alt"></i>
                    </button>

                    <button type="button"
                      class="btn btn-success btn-sm"
                      data-bs-toggle="modal"
                      data-bs-target="#gambarAiModal{{ $patient->id }}"
                      title="Lihat Gambar Citra AI">
                      <i class="bi bi-cpu"></i>
                    </button>
                  </div>
                </td>
                <td>
                  @if($patient->komentar)
                    {{ $patient->komentar }}
                  @else
                    <span class="badge bg-warning text-dark">Belum dikomentari</span>
                  @endif
                </td>
                <td>{{ $patient->citra->ket_nakes ?? '-' }}</td>
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
                  <div class="btn-group" role="group" aria-label="Kelola Citra Pasien">
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $patient->id }}" title="Verifikasi Pasien">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-sebelum-{{ $patient->id }}" title="Edit Citra Sebelum">
                      <i class="bi bi-image"></i>
                    </button>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-sesudah-{{ $patient->id }}" title="Edit Citra Sesudah">
                      <i class="bi bi-image-fill"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-ai-{{ $patient->id }}" title="Edit Citra AI">
                      <i class="bi bi-cpu"></i>
                    </button>
                  </div>
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
                <p class="text-muted">Belum ada gambar citra sebelum yang tersedia untuk pasien ini.</p>
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
                <p class="text-muted">Belum ada gambar citra sesudah yang tersedia untuk pasien ini.</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Gambar Citra AI -->
      <div class="modal fade" id="gambarAiModal{{ $patient->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Gambar Citra AI</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
              @if ($patient->citra && $patient->citra->citra_ai)
                <img src="{{ asset('storage/' . $patient->citra->citra_ai) }}" class="img-fluid">
              @else
                <p class="text-muted">Belum ada gambar citra AI yang tersedia untuk pasien ini.</p>
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

    @foreach ($patients as $patient)
      @php
        $imageTypes = [
          'sebelum' => $patient->citra->citra_sebelum ?? null,
          'sesudah' => $patient->citra->citra_sesudah ?? null,
          'ai'      => $patient->citra->citra_ai ?? null,
        ];
      @endphp

      @foreach ($imageTypes as $type => $imagePath)
      <!-- Modal Gambar {{ ucfirst($type) }} -->
      <div class="modal fade" id="modal-{{ $type }}-{{ $patient->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Anotasi Gambar {{ ucfirst($type) }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
              @if ($imagePath)
              <div id="draw-wrapper-{{ $type }}-{{ $patient->id }}" style="position: relative; display: inline-block;">
                <img id="img-{{ $type }}-{{ $patient->id }}" 
                    src="{{ asset('storage/' . $imagePath) }}" 
                    crossorigin="anonymous" style="max-width: 100%; max-height: 45vh; height: auto; display: block; margin: 0 auto;">
                <canvas id="canvas-{{ $type }}-{{ $patient->id }}" style="position: absolute; top: 0; left: 0;"></canvas>
              </div>
              @else
              <p class="text-muted">
                Belum ada gambar citra {{ ucfirst($type) }} yang tersedia untuk pasien ini.
              </p>
              @endif
            </div>
            <div class="modal-footer">
              @if ($imagePath)
                <button class="btn btn-warning btn-sm" onclick="undoDraw('{{ $type }}', {{ $patient->id }})">Undo Drawing</button>
                <button class="btn btn-secondary btn-sm" onclick="redoDraw('{{ $type }}', {{ $patient->id }})">Redo Drawing</button>
                <button class="btn btn-danger btn-sm" onclick="clearDraw('{{ $type }}', {{ $patient->id }})">Hapus Semua Drawing</button>
                <button onclick="updateCanvas({{ $patient->id }}, '{{ $type }}')" class="btn btn-primary btn-sm">Update</button>
              @else
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach

    @endforeach
    @endforeach
  </div>
</div>


<script>
  const states = {}; // undo states
  const redos = {};  // redo states

  function initCanvas(type, id) {
    const key = `${type}-${id}`;
    const img = document.getElementById(`img-${key}`);
    const canvas = document.getElementById(`canvas-${key}`);
    const ctx = canvas.getContext('2d');

    img.onload = () => {
      canvas.width = img.clientWidth;
      canvas.height = img.clientHeight;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      states[key] = [ctx.getImageData(0, 0, canvas.width, canvas.height)];
      redos[key] = []; 

      let drawing = false;
      canvas.onmousedown = (e) => {
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
      };
      canvas.onmousemove = (e) => {
        if (!drawing) return;
        ctx.lineWidth = 2;
        ctx.strokeStyle = 'red';
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
      };
      canvas.onmouseup = () => {
        if (drawing) {
          drawing = false;
          states[key].push(ctx.getImageData(0, 0, canvas.width, canvas.height));
          redos[key] = []; 
        }
      };
      canvas.onmouseleave = () => drawing = false;
    };

    if (img.complete) {
      img.onload();
    }
  }

  function undoDraw(type, id) {
    const key = `${type}-${id}`;
    const canvas = document.getElementById(`canvas-${key}`);
    const ctx = canvas.getContext('2d');
    if (states[key]?.length > 1) {
      const last = states[key].pop();
      redos[key].push(last);
      const prev = states[key][states[key].length - 1];
      ctx.putImageData(prev, 0, 0);
    }
  }

  function redoDraw(type, id) {
    const key = `${type}-${id}`;
    const canvas = document.getElementById(`canvas-${key}`);
    const ctx = canvas.getContext('2d');
    if (redos[key]?.length) {
      const next = redos[key].pop();
      states[key].push(next);
      ctx.putImageData(next, 0, 0);
    }
  }

function clearDraw(type, id) {
  const key = `${type}-${id}`;
  const canvas = document.getElementById(`canvas-${key}`);
  const ctx = canvas.getContext('2d');
  const img = document.getElementById(`img-${key}`);

  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.drawImage(img, 0, 0, canvas.width, canvas.height); // Tambahkan baris ini
  states[key] = [ctx.getImageData(0, 0, canvas.width, canvas.height)];
  redos[key] = [];
}

  function updateCanvas(patientId, type) {
    const canvas = document.getElementById(`canvas-${type}-${patientId}`);
    if (!canvas) return alert("Canvas tidak ditemukan");

    const imageData = canvas.toDataURL("image/png");

    fetch(`/dokter/citra/update/${patientId}/${type}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ image: imageData }),
    })
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      iziToast.success({ title: 'Berhasil', message: data.message, position: 'topCenter'});
      const modalId = `modal-${type}-${patientId}`;
      const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
      if (modal) modal.hide();
      setTimeout(() => location.reload(), 1000);
    })
    .catch(err => {
      iziToast.error({ title: 'Gagal', message: err.message });
      console.error('ERROR:', err);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

    @foreach ($patients as $patient)
      const imageTypes{{ $patient->id }} = @json([
        'sebelum' => optional($patient->citra)->citra_sebelum != null,
        'sesudah' => optional($patient->citra)->citra_sesudah != null,
        'ai' => optional($patient->citra)->citra_ai != null
      ], JSON_OBJECT_AS_ARRAY);

      Object.entries(imageTypes{{ $patient->id }}).forEach(([type, exists]) => {
        if (exists) {
          const modalEl = document.getElementById(`modal-${type}-{{ $patient->id }}`);
          modalEl?.addEventListener('shown.bs.modal', function () {
            initCanvas(type, {{ $patient->id }});
          });
        }
      });
    @endforeach
  });

  $(document).ready(function () {
    $('#table-pasien').DataTable({
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
