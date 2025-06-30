<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CitraPasien;

class CitraController extends Controller
{
    public function update(Request $request, $id, $type)
    {
        $request->validate(['image' => 'required|string']);
        $imageData = $request->input('image');

        $path = "citra/$type/" . uniqid() . '.png';
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace(' ', '+', $image);
        Storage::disk('public')->put($path, base64_decode($image));

        $citra = CitraPasien::firstOrNew(['patient_id' => $id]);

        $column = "citra_$type";
        $citra->$column = $path;
        $citra->save();

        return response()->json(['message' => 'Citra berhasil diperbarui', 'path' => $path]);
    }
}
