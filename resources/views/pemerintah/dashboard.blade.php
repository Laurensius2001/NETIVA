<x-layout>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
  .legend { background: white; padding: 10px; line-height: 18px; color: #333; box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; font-size: 13px; }
  table.dataTable thead th { background-color: #175e54; color: white; }

  html, body {
    background-color: #eef8f0 !important;
  }
</style>

<div class="container py-4">
  <div class="col-md-12">
    <div class="px-2 py-2 rounded-3 shadow-lg mb-4" style="background-color: #eef8f0; font-size:22px; color: #175e54; font-weight: 700;">
      <h5 class="text-center fw-bold">Selamat datang di Dashboard Admin Pemerintahan</h5>
      <h5 class="text-center fw-bold mb-3">Peta Persebaran Kasus Kanker Serviks (Wilayah Jawa Barat)</h5>
      <div id="map" style="height: 690px;"></div>
    </div>

    <div class="px-4 py-4 h-100 rounded p-4 border-0 shadow-lg" style="background-color: #f6fbf7;">
      <div class="container-fluid">
        <h5 id="judul-data" class="fw-bold mb-3" style="display:none;">Daftar Pasien Kabupaten/Kota: <span id="nama-kabupaten"></span></h5>
        <div id="tabel-data" style="display:none;">
          <table id="pasienTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>NIK</th>
                <th>Nama</th>
              </tr>
            </thead>
            <tbody id="tabel-pasien-body"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const dataKabupaten = @json($dataKasus);
console.log(dataKabupaten)

const map = L.map('map').setView([-6.9, 107.6], 8);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

const legend = L.control({ position: 'bottomright' });
legend.onAdd = function (map) {
  const div = L.DomUtil.create('div', 'info legend');
  const grades = [
    { label: "0 – 9 kasus", color: "#4caf50" },
    { label: "10 – 49 kasus", color: "#ffeb3b" },
    { label: "50+ kasus", color: "#e41a1c" }
  ];
  grades.forEach(item => {
    div.innerHTML += `<i style="background:${item.color}; width:18px; height:18px; float:left; margin-right:8px; opacity:0.8;"></i>${item.label}<br>`;
  });
  return div;
};
legend.addTo(map);


function getColor(jumlah) {
  return jumlah >= 50 ? '#e41a1c' : // merah
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
    },
    click: function (e) {
      map.fitBounds(e.target.getBounds());
      tampilkanPasien(nama);
    }
  });
}

function tampilkanPasien(namaKabupaten) {
  document.getElementById('nama-kabupaten').innerText = namaKabupaten;
  document.getElementById('judul-data').style.display = 'block';
  document.getElementById('tabel-data').style.display = 'block';

  fetch(`/pemerintah/pasien-per-kabupaten?kabupaten=${encodeURIComponent(namaKabupaten)}`)
    .then(res => res.json())
    .then(data => {
      const table = $('#pasienTable');
      const tbody = document.getElementById('tabel-pasien-body');

      // Hancurkan DataTable sebelumnya (jika ada)
      if ($.fn.DataTable.isDataTable('#pasienTable')) {
        table.DataTable().clear().destroy();
      }

      // Kosongkan tbody
      tbody.innerHTML = '';

      // Isi data baru
      data.forEach(p => {
        tbody.innerHTML += `<tr><td>${p.nik}</td><td>${p.nama}</td></tr>`;
      });

      // Inisialisasi ulang DataTable
      table.DataTable({
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
}


let geojson;
fetch('/maps/jabar_kabupaten.geojson')
  .then(res => res.json())
  .then(data => {
    geojson = L.geoJson(data, {
      style: style,
      onEachFeature: onEachFeature
    }).addTo(map);
    geojson.eachLayer(layer => geojson.resetStyle(layer));
  })
  .catch(err => console.error("Gagal memuat GeoJSON:", err));

</script>
</x-layout>
