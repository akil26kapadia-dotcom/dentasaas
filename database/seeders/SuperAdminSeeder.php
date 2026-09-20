<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('dentasaas.superadmin.email');
        $password = config('dentasaas.superadmin.password');

        if (! $email || ! $password) {
            $this->command?->error('Set SUPERADMIN_EMAIL and SUPERADMIN_PASSWORD in .env first (and clear the config cache). Nothing was created.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('dentasaas.superadmin.name'),
                'password' => Hash::make($password),
                'role' => 'superadmin',
                'clinic_id' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
