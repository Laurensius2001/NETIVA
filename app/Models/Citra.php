<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citra extends Model
{
    protected $table = 'citra_pasiens'; // sesuai nama tabel kamu
    protected $fillable = ['patient_id', 'citra_sebelum', 'citra_sesudah', 'citra_ai'];
}