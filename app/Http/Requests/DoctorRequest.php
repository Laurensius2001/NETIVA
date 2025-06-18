<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan ini true agar tidak forbidden
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'spesialis' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ];
    }
}
