<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<x-layout>
<div class="container mt-4">
  <h4>Selamat Datang sdr. {{ $patient->nama }}</h4>
  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card">
        <div class="card-body">
          <ul class="list-group">
            <li class="list-group-item active">Detail Pasien</li>
            <li class="list-group-item"><strong>NIK :</strong> {{ $patient->nik }}</li>
            <li class="list-group-item"><strong>Nama :</strong> {{ $patient->nama }}</li>
            <li class="list-group-item"><strong>Email :</strong> {{ $patient->user->email }}</li>
            <li class="list-group-item"><strong>Tempat Lahir :</strong> {{ $patient->tempat_lahir }}</li>
            <li class="list-group-item"><strong>Tanggal Lahir :</strong> {{ $patient->tanggal_lahir }}</li>
            <li class="list-group-item"><strong>Provinsi :</strong> {{ $patient->provinsi }}</li>
            <li class="list-group-item"><strong>Kabupaten :</strong> {{ $patient->kabupaten }}</li>
            <li class="list-group-item"><strong>Kecamatan :</strong> {{ $patient->kecamatan }}</li>
            <li class="list-group-item"><strong>Kelurahan :</strong> {{ $patient->kelurahan }}</li>
            <li class="list-group-item"><strong>RT/RW :</strong> {{ $patient->rt_rw }}</li>
            <li class="list-group-item"><strong>Alamat :</strong> {{ $patient->alamat }}</li>
            <li class="list-group-item"><strong>Pekerjaan:</strong> {{ $patient->pekerjaan }}</li>
            <li class="list-group-item"><strong>Status Verifikasi :</strong> 
              <span class="badge 
                @if ($patient->verifikasi === 'positif') bg-danger 
                @elseif($patient->verifikasi === 'negatif') bg-info text-dark 
                @else bg-secondary 
                @endif">
                {{ ucfirst($patient->verifikasi ?? '-') }}
              </span>
            </li>
            <li class="list-group-item">
              <strong>Foto Sebelum:</strong>
              <button class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalFotoSebelum">
                Lihat Gambar
              </button>
            </li>
            <li class="list-group-item">
              <strong>Foto Sesudah:</strong>
              <button class="btn btn-sm btn-outline-success ms-2" data-bs-toggle="modal" data-bs-target="#modalFotoSesudah">
                Lihat Gambar
              </button>
            </li>
            <li class="list-group-item"><strong>Komentar Dokter :</strong> @if($patient->komentar)
                                {{ $patient->komentar }}
                              @else
                                <span class="badge bg-warning text-dark">Belum dikomentari</span>
                              @endif</li>
          </ul>
          <div class="modal fade" id="modalFotoSebelum" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Foto Sebelum</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                  @if ($patient->citra)
                    <img src="{{ asset('storage/' . $patient->citra->citra_sebelum) }}" class="img-fluid rounded border">
                  @else
                    <p class="text-muted">Belum ada gambar.</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="modalFotoSesudah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Foto Sesudah</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                  @if ($patient->citra)
                    <img src="{{ asset('storage/' . $patient->citra->citra_sesudah) }}" class="img-fluid rounded border">
                  @else
                    <p class="text-muted">Belum ada gambar.</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Kolom maps -->
   <div class="col-md-8">
      <div class="px-2 py-2 bg-light rounded-3">
        <h5 class="text-center fw-bold mb-3">Peta Persebaran Kasus Kanker Serviks (Wilayah Jawa Barat)</h5>
        <div id="map" style="height: 690px;"></div>
      </div>
    </div>

  </div>
</div>

<script>
  const map = L.map('map').setView([-6.9, 107.6], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    const dataKabupaten = {
      "BANDUNG": 56,
      "BANDUNG BARAT": 34,
      "BANJAR": 12,
      "BEKASI": 70,
      "BOGOR": 63,
      "CIAMIS": 28,
      "CIANJUR": 39,
      "CIREBON": 31,
      "DEPOK": 45,
      "GARUT": 50,
      "INDRAMAYU": 42,
      "KARAWANG": 36,
      "KOTA BANDUNG": 48,
      "KOTA BEKASI": 40,
      "KOTA BOGOR": 33,
      "KOTA CIMAHI": 21,
      "KOTA CIREBON": 25,
      "KOTA DEPOK": 38,
      "KOTA SUKABUMI": 17,
      "KOTA TASIKMALAYA": 22,
      "KUNINGAN": 27,
      "MAJALENGKA": 19,
      "PANGANDARAN": 15,
      "PURWAKARTA": 18,
      "SUBANG": 26,
      "SUKABUMI": 35,
      "SUMEDANG": 24,
      "TASIKMALAYA": 29
    };
    function getColor(jumlah) {
      return jumlah > 60 ? '#800026' :
            jumlah > 40 ? '#BD0026' :
            jumlah > 30 ? '#E31A1C' :
            jumlah > 20 ? '#FC4E2A' :
            jumlah > 10 ? '#FD8D3C' :
            jumlah > 0  ? '#FEB24C' :
                          '#FFEDA0';
    }
    function style(feature) {
      const nama = (feature.properties.KABKOT || '').trim();
      const jumlah = dataKabupaten[nama] || 0;
      return {
        fillColor: getColor(jumlah),
        weight: 1,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7
      };
    }

    function onEachFeature(feature, layer) {
      const nama = (feature.properties.KABKOT || '-').trim();
      const jumlah = dataKabupaten[nama] || 0;
      layer.bindPopup(`<strong>${nama}</strong><br>${jumlah} kasus kanker serviks`);

      layer.on({
        mouseover: function (e) {
          e.target.setStyle({
            weight: 3,
            color: '#666',
            fillOpacity: 0.9
          });
          e.target.openPopup();
        },
        mouseout: function (e) {
          geojson.resetStyle(e.target);
          e.target.closePopup();
        },
        click: function (e) {
          map.fitBounds(e.target.getBounds());
        }
      });
    }

    let geojson;

    fetch('/maps/jabar_kabupaten.geojson')
      .then(res => res.json())
      .then(data => {
        geojson = L.geoJson(data, {
          style: style,
          onEachFeature: onEachFeature
        }).addTo(map);
      })
      .catch(err => console.error("Gagal memuat GeoJSON:", err));
</script>
</x-layout>
