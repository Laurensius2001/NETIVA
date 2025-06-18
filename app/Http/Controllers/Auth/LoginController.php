<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Override agar Auth::attempt() pakai 'username' bukan 'email'
    public function username()
    {
        return 'username';
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi form
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role'     => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        // Ambil user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Cek user dan password
        if ($user && Hash::check($request->password, $user->password)) {
            // Cek role yang dipilih sesuai dengan user
            if ($user->role !== $request->role) {
                return back()->withErrors([
                    'role' => 'Role tidak sesuai dengan akun.',
                ])->withInput();
            }

            // Login dan redirect sesuai role
            Auth::login($user);
            $request->session()->regenerate();

            return match ($user->role) {
                'admin'  => redirect('/admin/dashboard'),
                'dokter' => redirect('/dokter/dashboard'),
                'pasien' => redirect('/pasien/dashboard'),
                default  => redirect('/login')->withErrors(['role' => 'Role tidak dikenali.']),
            };
        }

        // Jika gagal login
        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
