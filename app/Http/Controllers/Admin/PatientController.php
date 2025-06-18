<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PatientController extends Controller{
  public function index(){
    $patients = Patient::with('user')->get();
    return view('admin.patients.index', compact('patients'));
  }

  public function create(){
    return view('admin.patients.create');
  }

  public function store(Request $request){
    $request->validate([
      'nama' => 'required|string|max:255',
      'umur' => 'required|integer',
      'tempat_lahir' => 'required|string|max:255',
      'tanggal_lahir' => 'required|date',
      'alamat' => 'required|string',
      'nik' => 'required|string|max:20',
      'provinsi' => 'required|string',
      'kabupaten' => 'required|string',
      'kecamatan' => 'required|string',
      'kelurahan' => 'required|string',
      'rt_rw' => 'required|string|max:20',
      'pekerjaan' => 'required|string|max:255',
      'golongan_darah' => 'required|string|max:3',
      'email' => 'required|email|unique:users,email',
      'username' => 'required|string|unique:users,username',
      'password' => 'required|string|min:6',
    ]);

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
    $user = User::create([
      'name' => $request->nama,
      'email' => $request->email,
      'username' => $request->username,
      'password' => Hash::make($request->password),
      'role' => 'pasien',
    ]);

      Patient::create([
        'user_id' => $user->id,
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
      ]);
      return redirect()->route('admin.patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function edit($id)
    {
      $patient = Patient::findOrFail($id);
      return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id){
      $patient = Patient::with('user')->findOrFail($id);
      $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email',
        'umur' => 'required|integer',
        'tempat_lahir' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'required|string',
        'rt_rw' => 'nullable|string',
        'pekerjaan' => 'nullable|string',
        'golongan_darah' => 'nullable|string',
        'username' => 'required|string',
        'provinsi' => 'required',
        'kabupaten' => 'required',
        'kecamatan' => 'required',
        'kelurahan' => 'required',
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
      $patient->update([
        'nama' => $validated['nama'],
        'umur' => $validated['umur'],
        'tempat_lahir' => $validated['tempat_lahir'],
        'tanggal_lahir' => $validated['tanggal_lahir'],
        'alamat' => $validated['alamat'],
        'rt_rw' => $validated['rt_rw'],
        'pekerjaan' => $validated['pekerjaan'],
        'golongan_darah' => $validated['golongan_darah'],
        'provinsi' => $provinsi,
        'kabupaten' => $kabupaten,
        'kecamatan' => $kecamatan,
        'kelurahan' => $kelurahan,
      ]);
        $user = $patient->user;
        if ($user) {
          $user->name = $request->nama;
          $user->email = $request->email;
          $user->username = $request->username;
          if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
          }
          $user->save();
        }
        return redirect()->back()->with('success', 'Data pasien dan akun berhasil diperbarui.');
    }
    public function destroy(Patient $patient)
    {
      $patient->user()->delete();
      $patient->delete();
      return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
