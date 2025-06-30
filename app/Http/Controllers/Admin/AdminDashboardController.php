<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\CitraPasien;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $jumlahDokter = Doctor::count();
        $jumlahPasien = Patient::count();
        $jumlahCitra = CitraPasien::count();

        $dataKasus = DB::table('patients')
            ->select('kabupaten', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kabupaten')
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtoupper($item->kabupaten) => $item->jumlah];
            });

        return view('admin.dashboard', compact('jumlahDokter', 'jumlahPasien', 'jumlahCitra', 'dataKasus'));
    }
}
