<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewPatientNotification;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        $doctors = \App\Models\Doctor::all();
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')->json();
        return view('admin.patients.index', compact('patients', 'doctors', 'provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'umur' => 'required|integer',
            'nik' => 'required|string',
            'rt_rw' => 'nullable|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'pekerjaan' => 'nullable|string',
            'golongan_darah' => 'nullable|string',
            'doctor_id' => 'nullable|string',
        ]);

        // Convert kode wilayah menjadi nama wilayah dari API
        $provinsi_nama = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json')
            ->collect()
            ->firstWhere('id', $request->provinsi)['name'] ?? null;

        $kabupaten_nama = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$request->provinsi}.json")
            ->collect()
            ->firstWhere('id', $request->kabupaten)['name'] ?? null;

        $kecamatan_nama = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$request->kabupaten}.json")
            ->collect()
            ->firstWhere('id', $request->kecamatan)['name'] ?? null;

        $kelurahan_nama = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$request->kecamatan}.json")
            ->collect()
            ->firstWhere('id', $request->kelurahan)['name'] ?? null;
        
       $patient = Patient::create([
          'nama' => $request->nama,
          'umur' => $request->umur,
          'nik' => $request->nik,
          'rt_rw' => $request->rt_rw,
          'tempat_lahir' => $request->tempat_lahir,
          'tanggal_lahir' => $request->tanggal_lahir,
          'alamat' => $request->alamat,
          'provinsi' => $provinsi_nama,
          'kabupaten' => $kabupaten_nama,
          'kecamatan' => $kecamatan_nama,
          'kelurahan' => $kelurahan_nama,
          'pekerjaan' => $request->pekerjaan,
          'golongan_darah' => $request->golongan_darah,
          'doctor_id' => $request->doctor_id,
        ]);

        if ($patient->doctor && $patient->doctor->user && $patient->doctor->user->email) {
            \Mail::to($patient->doctor->user->email)->send(new NewPatientNotification($patient));
        }

        return redirect()->route('admin.patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
      $patient = Patient::findOrFail($id);

      $oldDoctorId = $patient->doctor_id; // Simpan dulu doctor_id lama
        $request->validate([
          'nama' => 'required|string',
          'doctor_id' => 'required|string',
          'umur' => 'required|integer',
          'tempat_lahir' => 'required|string',
          'tanggal_lahir' => 'required|date',
          'alamat' => 'required|string',
          'rt_rw' => 'nullable|string',
          'pekerjaan' => 'nullable|string',
          'golongan_darah' => 'nullable|string',
          'provinsi' => 'required|string',
          'kabupaten' => 'required|string',
          'kecamatan' => 'required|string',
          'kelurahan' => 'required|string',
          'doctor_id' => 'nullable|exists:doctors,id',
        ]);

        $provinsi = is_numeric($request->provinsi)
            ? Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/province/{$request->provinsi}.json")->json()['name'] ?? $request->provinsi
            : $request->provinsi;

        $kabupaten = is_numeric($request->kabupaten)
            ? Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regency/{$request->kabupaten}.json")->json()['name'] ?? $request->kabupaten
            : $request->kabupaten;
        
        $kecamatan = is_numeric($request->kecamatan)
            ? Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/district/{$request->kecamatan}.json")->json()['name'] ?? $request->kecamatan
            : $request->kecamatan;

        $kelurahan = is_numeric($request->kelurahan)
            ? Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/village/{$request->kelurahan}.json")->json()['name'] ?? $request->kelurahan
            : $request->kelurahan;

        $patient = Patient::findOrFail($id);
        $patient->update([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'rt_rw' => $request->rt_rw,
            'pekerjaan' => $request->pekerjaan,
            'golongan_darah' => $request->golongan_darah,
            'provinsi' => $provinsi,
            'kabupaten' => $kabupaten,
            'kecamatan' => $kecamatan,
            'kelurahan' => $kelurahan,
            'doctor_id' => $request->doctor_id,
        ]);
        if ($oldDoctorId != $request->doctor_id && $request->doctor_id) {
            $newDoctor = \App\Models\Doctor::with('user')->find($request->doctor_id);
            if ($newDoctor && $newDoctor->user && $newDoctor->user->email) {
              \Mail::to($newDoctor->user->email)->send(new \App\Mail\NewPatientNotification($patient));
            }
          }
        return redirect()->back()->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil dihapus.');
    }

}
