<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;


class DoctorDashboardController extends Controller{
  public function index(){
    $doctor = auth()->user()->doctor;

    // Ambil hanya pasien yang ditangani oleh dokter ini saja
    $patients = Patient::with(['user', 'citra'])
                ->where('doctor_id', $doctor->id)
                ->get();
    return view('dokter.dashboard', compact('doctor', 'patients'));
  }

  public function verify(Request $request, Patient $patient){
    $request->validate([
      'verifikasi' => 'required|in:positif,negatif'
    ]);

    $patient->update([
      'verifikasi' => $request->verifikasi
    ]);

    return back()->with('success', 'Status verifikasi pasien berhasil diperbarui.');
  }
  public function updateCommentAndVerification(Request $request, Patient $patient){
    $request->validate([
      'komentar' => 'nullable|string',
      'verifikasi' => 'required|in:positif,negatif',
    ]);

    $patient->komentar = $request->komentar;
    $patient->verifikasi = $request->verifikasi;
    $patient->save();

    return redirect()->route('dokter.dashboard')->with('success', 'Data berhasil diperbarui.');
  }

  public function updateAnotasi(Request $request, $id)
{
    $request->validate([
        'image' => 'required|string',
    ]);

    $patient = Patient::findOrFail($id);
    $img = $request->input('image');

    // Convert base64 ke file dan simpan
    $image = str_replace('data:image/png;base64,', '', $img);
    $image = str_replace(' ', '+', $image);
    $imageName = 'anotasi_' . $id . '_' . time() . '.png';
    \Storage::disk('public')->put('anotasi/' . $imageName, base64_decode($image));

    // Simpan path-nya di database (contoh kolom `anotasi_path`)
    $patient->anotasi_path = 'anotasi/' . $imageName;
    $patient->save();

    return response()->json(['success' => true]);
}

}
