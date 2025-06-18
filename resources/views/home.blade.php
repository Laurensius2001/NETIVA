<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<x-layout title="Home">
  <div class="gradient-bg container py-5 d-flex flex-column flex-md-row align-items-center justify-content-between">
    {{-- Kiri: Teks --}}
    <div class="text-section text-center text-md-start mb-4 mb-md-0" style="max-width: 50%;">
      <h1 class="display-5 fw-bold text-primary">Selamat Datang di Sistem Deteksi Kanker Serviks</h1>
      <p class="lead mt-3">
        Kami percaya bahwa akses terhadap informasi dan pemeriksaan kesehatan yang tepat waktu adalah kunci untuk
        mengurangi risiko kanker serviks. Dengan aplikasi ini, mari kita wujudkan masyarakat yang lebih sehat, sadar,
        dan peduli terhadap kesehatan reproduksi perempuan.
      </p>
      <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Mulai Sekarang</a>
    </div>

    {{-- Kanan: Gambar --}}
    <div class="image-section text-center" style="max-width: 45%;">
      <img src="{{ asset('images/undraw_doctor.svg') }}" alt="Loan Illustration" class="img-fluid"
        style="max-height: 400px;">
    </div>
  </div>
  <div class="col-md-12">
      <div class="px-2 py-2 bg-light rounded-3">
        <div class="container-fluid"
          <div id="map" style="height: 500px;"></div>
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