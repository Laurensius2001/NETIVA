<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
  return view('home');
});

Route::post('/login', [LoginController::class, 'login']);

Route::get('/login', function () {
  return view('login');
})->name('login');

// Route untuk login
Route::get('/login', fn() => view('login'))->name('login');

Route::middleware(['auth', 'role:admin'])->group(function () {
  Route::get('/admin/dashboard', fn() => view('admin.dashboard'));
});


Route::middleware(['auth', 'role:pasien'])->group(function () {
  Route::get('/pasien/dashboard', fn() => view('pasien.dashboard'));
});


// DOCTER
use App\Http\Controllers\Admin\DoctorController;
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('admin.doctors.index');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('admin.doctors.store');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('admin.doctors.update');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('admin.doctors.destroy');
});


use App\Http\Controllers\Admin\PatientController;
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
  Route::get('/patients', [PatientController::class, 'index'])->name('admin.patients.index');
  Route::post('/patients', [PatientController::class, 'store'])->name('admin.patients.store');
  Route::put('/patients/{id}', [PatientController::class, 'update'])->name('admin.patients.update');
  Route::delete('/admin/patients/{patient}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');
});


use App\Http\Controllers\Admin\PatientCitraController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
  Route::get('/citra', [PatientCitraController::class, 'index'])->name('admin.citra.index');
  Route::post('/citra', [PatientCitraController::class, 'store'])->name('admin.citra.store');
  Route::put('/citra/{id}', [PatientCitraController::class, 'update'])->name('admin.citra.update');
  Route::delete('/citra/{id}', [PatientCitraController::class, 'destroy'])->name('admin.citra.destroy');
});

use App\Http\Controllers\DoctorDashboardController;
Route::middleware(['auth', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::put('/patients/{patient}/verify', [DoctorDashboardController::class, 'verify'])->name('patients.verify');
    Route::put('/patients/{patient}/komentar-verifikasi', [DoctorDashboardController::class, 'updateCommentAndVerification'])->name('update.komentar.verifikasi');
});


use App\Http\Controllers\PatientDashboardController;

Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
});
