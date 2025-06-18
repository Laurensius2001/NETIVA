<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<x-layout>
<div class="container mt-4">
  <h4>Selamat Datang sdr. {{ $patient->nama }}</h4>
  <div class="row">
    <div class="col-md-4">
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
            <li class="list-group-item"><strong>Komentar Dokter :</strong> {{ $patient->komentar }}</li>
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
        <div class="container-fluid"
          <div id="map" style="height: 690px;"></div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  var map = L.map('map').setView([-2.5, 117.5], 5);

  // Tambahkan tile layer dari OSM
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  // Data jumlah kasus per provinsi (gunakan lowercase dan sesuai GeoJSON)
  const dataProvinsi = {
    "aceh": 6480 ,
    "sumatera utara": 18000 ,
    "sumatera barat": 6600 ,
    "riau": 8040 ,
    "jambi": 4560 ,
    "sumatera selatan": 7,
    "bengkulu": 2,
    "lampung": 6,
    "dki jakarta": 20,
    "jawa barat": 30,
    "jawa tengah": 25,
    "di yogyakarta": 8,
    "jawa timur": 18,
    "bali": 4,
    "kalimantan barat": 6,
    "kalimantan tengah": 3,
    "kalimantan selatan": 4,
    "kalimantan timur": 5,
    "sulawesi utara": 2,
    "sulawesi tengah": 3,
    "sulawesi selatan": 7,
    "papua": 1,
    "nusa tenggara timur": 15
  };

  // Fungsi warna acak tetap konsisten berdasarkan nama
  function getColor(nama) {
    const colors = ['#FF5733', '#33FF57', '#3357FF', '#F39C12', '#8E44AD'];
    const hash = [...nama].reduce((acc, char) => acc + char.charCodeAt(0), 0);
    return colors[hash % colors.length];
  }

  // Style setiap provinsi
  function style(feature) {
    const nama = (feature.properties.Propinsi || '-').toLowerCase().trim();
    return {
      fillColor: getColor(nama),
      weight: 1,
      opacity: 1,
      color: 'white',
      dashArray: '3',
      fillOpacity: 0.7
    };
  }

  // Interaksi provinsi
  function onEachFeature(feature, layer) {
    const properName = feature.properties.Propinsi || '-';
    const nama = properName.toLowerCase().trim();
    const jumlah = dataProvinsi[nama] || 0;

    layer.bindPopup(`<strong>${properName}</strong><br>${jumlah} kasus kanker serviks`);

    layer.on({
      mouseover: function (e) {
        var layer = e.target;
        layer.setStyle({
          weight: 2,
          color: '#333',
          fillOpacity: 0.9
        });
        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
          layer.bringToFront();
        }
        layer.openPopup();
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

  var geojson;

  // Ambil file GeoJSON dan render ke peta
  fetch('/maps/indonesia-prov.geojson')
    .then(response => {
      if (!response.ok) {
        throw new Error("GeoJSON tidak ditemukan");
      }
      return response.json();
    })
    .then(geoData => {
      geojson = L.geoJson(geoData, {
        style: style,
        onEachFeature: onEachFeature
      }).addTo(map);
    })
    .catch(error => {
      console.error('Gagal memuat GeoJSON:', error);
    });
</script>



</x-layout>
