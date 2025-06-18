<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;


class DoctorDashboardController extends Controller{
  public function index(){
    $doctor = auth()->user()->doctor;
    $patients = Patient::with(['user', 'citra'])->get();
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
}
