<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CitraPasien;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;

class PatientCitraController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        $citras = CitraPasien::with('patient')->get();

        return view('admin.citra.index', compact('patients', 'citras'));
    }

    public function store(Request $request)
    {
        $request->validate([
          'patient_id' => 'required|exists:patients,id',
          'sebelum' => 'required|image|mimes:jpeg,png,jpg|max:10240',
          'sesudah' => 'required|image|mimes:jpeg,png,jpg|max:10240',
          'citra_ai' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
          'ket_nakes' => 'nullable|string',
        ]);

        // Pastikan direktori ada
          foreach (['citra/sebelum', 'citra/sesudah', 'citra/ai'] as $folder) {
              if (!Storage::exists("public/$folder")) {
                  Storage::makeDirectory("public/$folder");
              }
          }

          // Simpan file dengan nama unik
          $sebelumPath = $request->file('sebelum')->storeAs(
              'citra/sebelum',
              uniqid() . '.' . $request->file('sebelum')->getClientOriginalExtension(),
              'public'
          );

          $sesudahPath = $request->file('sesudah')->storeAs(
              'citra/sesudah',
              uniqid() . '.' . $request->file('sesudah')->getClientOriginalExtension(),
              'public'
          );

          $citraAiPath = null;
          if ($request->hasFile('citra_ai')) {
              $citraAiPath = $request->file('citra_ai')->storeAs(
                  'citra/ai',
                  uniqid() . '.' . $request->file('citra_ai')->getClientOriginalExtension(),
                  'public'
              );
          }

        // Simpan ke database
        CitraPasien::create([
            'patient_id' => $request->patient_id,
            'citra_sebelum' => $sebelumPath,
            'citra_sesudah' => $sesudahPath,
            'citra_ai' => $citraAiPath,
            'ket_nakes' => $request->ket_nakes,
        ]);

        return redirect()->route('admin.citra.index')->with('success', 'Citra berhasil ditambahkan.');
    }

    public function update(Request $request, $id){
      $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'sebelum' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        'sesudah' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        'citra_ai' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        'ket_nakes' => 'nullable|string',
      ]);

      $citra = CitraPasien::findOrFail($id);

      if ($request->hasFile('sebelum')) {
          Storage::disk('public')->delete($citra->citra_sebelum);
          $citra->citra_sebelum = $request->file('sebelum')->store('citra/sebelum', 'public');
      }

      if ($request->hasFile('sesudah')) {
          Storage::disk('public')->delete($citra->citra_sesudah);
          $citra->citra_sesudah = $request->file('sesudah')->store('citra/sesudah', 'public');
      }

      if ($request->hasFile('citra_ai')) {
          if ($citra->citra_ai) {
              Storage::disk('public')->delete($citra->citra_ai);
          }
          $citra->citra_ai = $request->file('citra_ai')->store('citra/ai', 'public');
      }
      $citra->ket_nakes = $request->ket_nakes;
      $citra->patient_id = $request->patient_id;
      $citra->save();

      return redirect()->route('admin.citra.index')->with('success', 'Citra berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $citra = CitraPasien::findOrFail($id);
        Storage::disk('public')->delete([$citra->citra_sebelum, $citra->citra_sesudah]);
        $citra->delete();

        return redirect()->back()->with('success', 'Citra pasien berhasil dihapus.');
    }
}
