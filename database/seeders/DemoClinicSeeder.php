<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\TreatmentPlan;
use App\Models\TreatmentSession;
use App\Models\User;
use App\Services\InvoiceService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoClinicSeeder extends Seeder
{
    public function run(): void
    {
        $clinic = Clinic::updateOrCreate(
            ['slug' => 'happy-smile'],
            [
                'name' => 'Happy Smile',
                'plan' => 'premium',
                'status' => 'active',
                'phone' => '+91 8488055253',
                'email' => 'contact@happysmile.example',
            ]
        );

        $doctor = User::updateOrCreate(
            ['email' => 'doctor@happysmile.com'],
            [
                'name' => 'Dr. Happy Smile',
                'password' => Hash::make('Doctor@2025'),
                'role' => 'admin',
                'clinic_id' => $clinic->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $patients = collect([
            ['name' => 'Aarav Shah', 'phone' => '9820011111', 'email' => 'aarav.shah@example.com', 'gender' => 'male'],
            ['name' => 'Priya Mehta', 'phone' => '9820022222', 'email' => 'priya.mehta@example.com', 'gender' => 'female'],
            ['name' => 'Rohan Verma', 'phone' => '9820033333', 'email' => 'rohan.verma@example.com', 'gender' => 'male'],
        ])->map(fn (array $data) => Patient::create([...$data, 'clinic_id' => $clinic->id]));

        $services = collect([
            ['name' => 'Tooth Cleaning', 'price' => 800, 'icon' => 'fa-tooth'],
            ['name' => 'Root Canal', 'price' => 3500, 'icon' => 'fa-tooth'],
            ['name' => 'Teeth Whitening', 'price' => 2000, 'icon' => 'fa-tooth'],
        ])->map(fn (array $data) => Service::create([...$data, 'clinic_id' => $clinic->id]));

        Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patients[0]->id,
            'user_id' => $doctor->id,
            'service_id' => $services[0]->id,
            'patient_name' => $patients[0]->name,
            'service_name' => $services[0]->name,
            'doctor_name' => $doctor->name,
            'appt_date' => today(),
            'appt_time' => '10:00',
            'status' => 'confirmed',
        ]);

        Appointment::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patients[1]->id,
            'user_id' => $doctor->id,
            'service_id' => $services[1]->id,
            'patient_name' => $patients[1]->name,
            'service_name' => $services[1]->name,
            'doctor_name' => $doctor->name,
            'appt_date' => today()->addDay(),
            'appt_time' => '11:30',
            'status' => 'pending',
        ]);

        $invoiceService = app(InvoiceService::class);

        $paidItems = [['service' => $services[0]->name, 'qty' => 1, 'price' => (float) $services[0]->price]];
        $paidTotals = $invoiceService->calculateTotals($paidItems, 0, 0);

        Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patients[0]->id,
            'patient_name' => $patients[0]->name,
            'invoice_no' => $invoiceService->generateInvoiceNo($clinic),
            'invoice_date' => today(),
            'items' => $paidItems,
            ...$paidTotals,
            'status' => 'paid',
        ]);

        $unpaidItems = [['service' => $services[1]->name, 'qty' => 1, 'price' => (float) $services[1]->price]];
        $unpaidTotals = $invoiceService->calculateTotals($unpaidItems, 0, 0);

        Invoice::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patients[1]->id,
            'patient_name' => $patients[1]->name,
            'invoice_no' => $invoiceService->generateInvoiceNo($clinic),
            'invoice_date' => today(),
            'items' => $unpaidItems,
            ...$unpaidTotals,
            'status' => 'unpaid',
        ]);

        $plan = TreatmentPlan::create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patients[1]->id,
            'patient_name' => $patients[1]->name,
            'doctor_name' => $doctor->name,
            'treatment' => 'Root Canal',
            'total_sessions' => 3,
            'status' => 'in_progress',
        ]);

        TreatmentSession::create([
            'clinic_id' => $clinic->id,
            'plan_id' => $plan->id,
            'session_no' => 1,
            'title' => 'Root Canal - Session 1',
            'scheduled_date' => today()->subDays(7),
            'doctor_name' => $doctor->name,
            'status' => 'completed',
            'is_paid' => true,
        ]);

        TreatmentSession::create([
            'clinic_id' => $clinic->id,
            'plan_id' => $plan->id,
            'session_no' => 2,
            'title' => 'Root Canal - Session 2',
            'scheduled_date' => today(),
            'doctor_name' => $doctor->name,
            'status' => 'planned',
        ]);

        TreatmentSession::create([
            'clinic_id' => $clinic->id,
            'plan_id' => $plan->id,
            'session_no' => 3,
            'title' => 'Root Canal - Session 3',
            'scheduled_date' => today()->addDays(7),
            'doctor_name' => $doctor->name,
            'status' => 'planned',
        ]);
    }
}
