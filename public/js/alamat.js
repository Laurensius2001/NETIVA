document.addEventListener('DOMContentLoaded', () => {
  const loadWilayah = () => {
    const sel = id => document.getElementById(id);
    const show = id => document.getElementById(`${id}-container`).classList.remove('d-none');

    fetch('https://api.datawilayah.com/api/provinsi.json')
      .then(r => r.json())
      .then(data => {
        sel('provinsi').innerHTML = `<option value="">Pilih Provinsi</option>`;
        data.forEach(p => sel('provinsi').innerHTML += `<option value="${p.id}">${p.nama}</option>`);
        show('provinsi');
      });

    sel('provinsi').onchange = () => {
      const provId = sel('provinsi').value;
      if (!provId) return;
      fetch(`https://api.datawilayah.com/api/kabupaten_kota/${provId}.json`)
        .then(r => r.json())
        .then(data => {
          sel('kota').innerHTML = `<option value="">Pilih Kota</option>`;
          data.forEach(k => sel('kota').innerHTML += `<option value="${k.id}">${k.nama}</option>`);
          show('kota');
        });
    };

    sel('kota').onchange = () => {
      const kotaId = sel('kota').value;
      if (!kotaId) return;
      fetch(`https://api.datawilayah.com/api/kecamatan/${kotaId}.json`)
        .then(r => r.json())
        .then(data => {
          sel('kecamatan').innerHTML = `<option value="">Pilih Kecamatan</option>`;
          data.forEach(k => sel('kecamatan').innerHTML += `<option value="${k.id}">${k.nama}</option>`);
          show('kecamatan');
        });
    };

    sel('kecamatan').onchange = () => {
      const kecId = sel('kecamatan').value;
      if (!kecId) return;
      fetch(`https://api.datawilayah.com/api/desa_kelurahan/${kecId}.json`)
        .then(r => r.json())
        .then(data => {
          sel('kelurahan').innerHTML = `<option value="">Pilih Kelurahan</option>`;
          sel('kode_pos').innerHTML = `<option value="">Pilih Kode Pos</option>`;
          data.forEach(kel => {
            sel('kelurahan').innerHTML += `<option value="${kel.id}">${kel.nama}</option>`;
            sel('kode_pos').innerHTML += `<option value="${kel.postal_code}">${kel.postal_code}</option>`;
          });
          show('kelurahan');
          show('kode_pos');
        });
    };
  };

  const modal = document.getElementById('createPatientModal');
  modal.addEventListener('shown.bs.modal', () => {
    loadWilayah();
  });
});
