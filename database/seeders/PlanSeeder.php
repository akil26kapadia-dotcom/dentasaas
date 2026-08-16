<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'key' => 'free',
                'name' => 'Free',
                'price_monthly' => 0,
                'patients_limit' => 25,
                'appointments_limit' => 50,
                'invoices_limit' => 10,
                'doctors_limit' => 1,
                'pdf_export' => false,
                'prescriptions' => false,
                'analytics' => 'none',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'key' => 'basic',
                'name' => 'Basic',
                'price_monthly' => 299,
                'patients_limit' => 100,
                'appointments_limit' => 200,
                'invoices_limit' => 50,
                'doctors_limit' => 2,
                'pdf_export' => true,
                'prescriptions' => true,
                'analytics' => 'basic',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'key' => 'premium',
                'name' => 'Premium',
                'price_monthly' => 799,
                'patients_limit' => 500,
                'appointments_limit' => 1000,
                'invoices_limit' => 200,
                'doctors_limit' => 5,
                'pdf_export' => true,
                'prescriptions' => true,
                'analytics' => 'full',
                'is_highlighted' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'key' => 'deluxe',
                'name' => 'Deluxe',
                'price_monthly' => 1499,
                'patients_limit' => -1,
                'appointments_limit' => -1,
                'invoices_limit' => -1,
                'doctors_limit' => -1,
                'pdf_export' => true,
                'prescriptions' => true,
                'analytics' => 'full',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['key' => $plan['key']], $plan);
        }
    }
}
