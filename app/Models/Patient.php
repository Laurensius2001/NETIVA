<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'komentar',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'umur',
        'rt_rw',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pekerjaan',
        'golongan_darah',
        'verifikasi',
        'doctor_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function citra()
    {
        return $this->hasOne(CitraPasien::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
