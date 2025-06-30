<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<style>
  .info.legend {
    background: white;
    padding: 10px;
    font-size: 12px;
    line-height: 18px;
    color: #333;
    max-height: 700px;
    overflow-y: auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
  }
</style>

@extends('layouts.admin')
@section('content')
<div class="container-fluid">
  <span class="badge rounded-pill px-4 py-2 mb-4 shadow-lg" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
    <i class="fa-solid fa-house-chimney"></i> Halaman Dashboard Nakes
  </span>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card shadow-lg border-0" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
        <div class="card-body d-flex align-items-center">
          <img src="{{ asset('images/dokter.png') }}" alt="Ilustrasi Dokter" class="me-3" style="height: 6rem;">
          <div>
            <h5 class="card-title fw-bold lead">Jumlah Dokter</h5>
            <h3>{{ $jumlahDokter }}</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-lg border-0" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
        <div class="card-body d-flex align-items-center">
          <img src="{{ asset('images/pasien.png') }}" alt="Ilustrasi Pasien" class="me-3" style="height: 6rem;">
          <div>
            <h5 class="card-title fw-bold lead">Jumlah Pasien</h5>
            <h3>{{ $jumlahPasien }}</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-lg border-0" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
        <div class="card-body d-flex align-items-center">
          <img src="{{ asset('images/citra.png') }}" alt="Ilustrasi Citra" class="me-3" style="height: 6rem;">
          <div>
            <h5 class="card-title fw-bold lead">Data Citra</h5>
            <h3>{{ $jumlahCitra }}</h3>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="px-2 py-2 shadow-lg rounded-3">
        <h5 class="text-center fw-bold mb-3" style="color: #175e54;">Peta Persebaran Kasus Kanker Serviks (Wilayah Jawa Barat)</h5>
        <div id="map" style="height: 690px;"></div>
      </div>
    </div>
  </div>

<script>
const dataKabupaten = @json($dataKasus);
console.log(dataKabupaten)

const warnaKabupaten = {
  "BANDUNG": "#e41a1c", "BANDUNG BARAT": "#377eb8", "BANJAR": "#4daf4a",
  "BEKASI": "#984ea3", "BOGOR": "#ff7f00", "CIAMIS": "#ffff33", "CIANJUR": "#a65628",
  "CIREBON": "#f781bf", "DEPOK": "#999999", "GARUT": "#66c2a5", "INDRAMAYU": "#fc8d62",
  "KARAWANG": "#8da0cb", "KOTA BANDUNG": "#e78ac3", "KOTA BEKASI": "#a6d854",
  "KOTA BOGOR": "#ffd92f", "KOTA CIMAHI": "#e5c494", "KOTA CIREBON": "#b3b3b3",
  "KOTA DEPOK": "#d9d9d9", "KOTA SUKABUMI": "#1f78b4", "KOTA TASIKMALAYA": "#33a02c",
  "KUNINGAN": "#fb9a99", "MAJALENGKA": "#cab2d6", "PANGANDARAN": "#ffffb3",
  "PURWAKARTA": "#bebada", "SUBANG": "#fdbf6f", "SUKABUMI": "#b15928",
  "SUMEDANG": "#6a3d9a", "TASIKMALAYA": "#ffb3b3"
};

const map = L.map('map').setView([-6.9, 107.6], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Legend
const legend = L.control({ position: 'bottomright' });
legend.onAdd = function (map) {
  const div = L.DomUtil.create('div', 'info legend');
  const grades = [
    { label: "0–9 kasus", color: "#4caf50" },
    { label: "10–49 kasus", color: "#ffeb3b" },
    { label: "50+ kasus", color: "#e41a1c" }
  ];
  grades.forEach(item => {
    div.innerHTML += `<i style="background:${item.color}; width:18px; height:18px; float:left; margin-right:8px; opacity:0.8;"></i>${item.label}<br>`;
  });
  return div;
};
legend.addTo(map);


// Styling & Popup
function getColor(jumlah) {
  return jumlah > 50 ? '#e41a1c' : // merah
         jumlah >= 10 ? '#ffeb3b' : // kuning
         '#4caf50';                // hijau
}

function style(feature) {
  const namaAsli = (feature.properties.KABKOT || '').trim();
  const nama = normalisasiNama(namaAsli);
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

function normalisasiNama(nama) {
  const namaBersih = nama.toUpperCase().trim();
  const listKota = [
    'KOTA BOGOR', 'KOTA SUKABUMI', 'KOTA BANDUNG', 'KOTA CIREBON',
    'KOTA BEKASI', 'KOTA DEPOK', 'KOTA CIMAHI', 'KOTA TASIKMALAYA', 'KOTA BANJAR'
  ];
  if (listKota.includes(namaBersih)) return namaBersih;
  return 'KABUPATEN ' + namaBersih;
}

function onEachFeature(feature, layer) {
  const namaAsli = (feature.properties.KABKOT || '-').trim();
  const nama = normalisasiNama(namaAsli);
  const jumlah = dataKabupaten[nama] || 0;

  layer.bindPopup(`<strong>${nama}</strong><br>${jumlah} kasus kanker serviks`);

  layer.on({
    mouseover: function (e) {
      e.target.setStyle({ weight: 3, color: '#666', fillOpacity: 0.9 });
      e.target.openPopup();
    },
    mouseout: function (e) {
      geojson.resetStyle(e.target);
      e.target.closePopup();
    }
    // Tidak ada handler "click"
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

@endsection
