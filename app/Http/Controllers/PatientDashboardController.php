<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        if (!$patient) {
            return abort(403, 'Akun ini belum terhubung ke data pasien.');
        }

        return view('pasien.dashboard', compact('patient'));
    }
}
