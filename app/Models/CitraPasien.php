<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitraPasien extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'citra_sebelum',
        'citra_sesudah'
        ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    }
