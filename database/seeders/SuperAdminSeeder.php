<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dentasaas.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Admin@2025'),
                'role' => 'superadmin',
                'clinic_id' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
