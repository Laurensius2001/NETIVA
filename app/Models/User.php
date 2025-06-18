<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'username', 'password', 'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Otomatis hash password jika belum pakai Laravel 10+ hash casting
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = Hash::make($value);
    // }

    // Relasi ke dokter (jika user adalah dokter)
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // Relasi ke pasien (jika user adalah pasien)
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
}
