<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Admin Nakes
        User::updateOrCreate(
            ['email' => 'nakes@example.com'],
            [
                'name' => 'Admin Nakes',
                'username' => 'nakes',
                'password' => Hash::make('12345'),
                'role' => 'admin',
            ]
        );

        // Admin Pemerintahan
        User::updateOrCreate(
            ['email' => 'pemerintah@netiva.id'],
            [
                'name' => 'Admin Pemerintahan',
                'username' => 'pemerintah',
                'password' => Hash::make('12345'),
                'role' => 'admin_pemerintah',
            ]
        );
    }
}
