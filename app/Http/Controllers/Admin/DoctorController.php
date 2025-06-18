<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\DoctorRequest;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function store(DoctorRequest $request)
      {
          \Log::info('DoctorController@store masuk');
          $request->validate([
              'nama' => 'required|string|max:255',
              'email' => 'required|email|unique:users,email',
              'username' => 'required|string|unique:users,username',
              'password' => 'required|string|min:6',
              'spesialis' => 'required|string|max:255',
              'institution' => 'nullable|string|max:255',
          ]);

          // Simpan user & dokter
          $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => 'dokter',
        ]);

          Doctor::create([
              'user_id' => $user->id,
              'nama' => $request->nama,
              'spesialis' => $request->spesialis,
              'institution' => $request->institution,
              'username' => $request->username, // Tambahkan ini jika memang dibutuhkan
              'password' => bcrypt($request->password),
          ]);

          \Log::info('Dokter berhasil disimpan');

              return redirect()->route('admin.doctors.index')->with('success', 'Dokter berhasil ditambahkan.');

      }

      public function update(Request $request, $id)
      {
          $doctor = Doctor::with('user')->findOrFail($id);

          $request->validate([
              'nama' => 'required|string|max:255',
              'spesialis' => 'required|string|max:255',
              'institution' => 'required|string|max:255',
              'username' => 'required|string|unique:users,username,' . $doctor->user_id,
          ]);

          // Update tabel doctors
          $doctor->update([
              'nama' => $request->nama,
              'spesialis' => $request->spesialis,
              'institution' => $request->institution,
              'username' => $request->username,
          ]);

          // Update tabel users juga
          if ($doctor->user) {
              $doctor->user->update([
                  'name' => $request->nama,
                  'username' => $request->username,
              ]);
          }

          return back()->with('success', 'Dokter dan akun user berhasil diperbarui.');
      }


    

    public function destroy($id)
      {
          $doctor = Doctor::findOrFail($id);

          // Hapus user terkait
          if ($doctor->user) {
              $doctor->user->delete();
          }

          // Hapus data dokter
          $doctor->delete();

          return back()->with('success', 'Dokter dan akun user berhasil dihapus.');
      }
}
