<?php

namespace App\Http\Controllers\Pemerintah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;

class DashboardController extends Controller
{
    public function index()
    {
        $dataKasus = DB::table('patients')
            ->select('kabupaten', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kabupaten')
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtoupper($item->kabupaten) => $item->jumlah];
            });

        return view('pemerintah.dashboard', compact('dataKasus'));
    }

    public function getPatientsByKabupaten(Request $request)
    {
        $kabupaten = $request->query('kabupaten');

        $patients = Patient::select('nik', 'nama')
            ->whereRaw('LOWER(kabupaten) = ?', [strtolower($kabupaten)])
            ->get();

        return response()->json($patients);
    }
}