<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;

beforeEach(fn () => $this->seed());

function happySmileDoctor(): User
{
    return User::where('email', 'doctor@happysmile.com')->first();
}

test('appointments index renders with quick filter counts', function () {
    $doctor = happySmileDoctor();

    $response = $this->actingAs($doctor)->get('/appointments');

    $response->assertOk();
    $response->assertSee('Appointments');
    $response->assertSee('New Appointment');
});

test('the today quick filter only returns appointments dated today', function () {
    $doctor = happySmileDoctor();
    $clinic = $doctor->clinic;

    Appointment::factory()->create(['clinic_id' => $clinic->id, 'appt_date' => now(), 'patient_name' => 'Today Patient']);
    Appointment::factory()->create(['clinic_id' => $clinic->id, 'appt_date' => now()->addDays(3), 'patient_name' => 'Future Patient']);

    $response = $this->actingAs($doctor)->get('/appointments?date=today');

    $response->assertOk();
    $response->assertSee('Today Patient');
    $response->assertDontSee('Future Patient');
});

test('appointment can be created via json and returns whatsapp url', function () {
    $doctor = happySmileDoctor();
    $patient = Patient::where('clinic_id', $doctor->clinic_id)->first();

    $response = $this->actingAs($doctor)->postJson('/appointments', [
        'patient_id' => $patient->id,
        'patient_name' => $patient->name,
        'service_name' => 'Consultation',
        'appt_date' => now()->addDay()->format('Y-m-d'),
        'appt_time' => '14:30',
        'notes' => 'Smoke test appointment',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['success', 'appointment', 'whatsapp_url']);
    expect($response->json('whatsapp_url'))->toContain('wa.me');

    $appointment = Appointment::where('notes', 'Smoke test appointment')->first();
    expect($appointment)->not->toBeNull();
    expect($appointment->clinic_id)->toBe($doctor->clinic_id);
});

test('appointment status can be updated and appointment can be deleted', function () {
    $doctor = happySmileDoctor();
    $appointment = Appointment::where('clinic_id', $doctor->clinic_id)->first();

    $this->actingAs($doctor)->patchJson("/appointments/{$appointment->id}/status", ['status' => 'confirmed'])
        ->assertOk()
        ->assertJsonPath('appointment.status', 'confirmed');

    $this->actingAs($doctor)->deleteJson("/appointments/{$appointment->id}")->assertOk();

    expect(Appointment::find($appointment->id))->toBeNull();
});

test('appointment creation is blocked once plan limit is reached', function () {
    $clinic = Clinic::create([
        'name' => 'Appt Limit Clinic',
        'slug' => 'appt-limit-clinic',
        'plan' => 'free',
        'status' => 'active',
    ]);

    $owner = User::create([
        'clinic_id' => $clinic->id,
        'name' => 'Appt Owner',
        'email' => 'owner@apptlimit.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    Appointment::factory()->count(50)->create([
        'clinic_id' => $clinic->id,
        'appt_date' => now(),
    ]);

    $response = $this->actingAs($owner)->postJson('/appointments', [
        'patient_name' => 'Overflow Patient',
        'service_name' => 'Cleaning',
        'appt_date' => now()->format('Y-m-d'),
        'appt_time' => '09:00',
    ]);

    $response->assertStatus(403);
    expect(Appointment::where('clinic_id', $clinic->id)->count())->toBe(50);
});

test('services index renders and a service can be created, toggled and deleted', function () {
    $doctor = happySmileDoctor();

    $this->actingAs($doctor)->get('/services')->assertOk();

    $response = $this->actingAs($doctor)->post('/services', [
        'name' => 'Dental Implant',
        'name_hi' => 'डेंटल इम्प्लांट',
        'price' => 15000,
        'duration_min' => 90,
        'icon' => 'fa-tooth',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('services.index'));

    $service = Service::where('name', 'Dental Implant')->first();
    expect($service)->not->toBeNull();
    expect($service->clinic_id)->toBe($doctor->clinic_id);

    $this->actingAs($doctor)->patch(route('services.toggle', $service))->assertRedirect();
    expect($service->fresh()->is_active)->toBeFalse();

    $this->actingAs($doctor)->delete(route('services.destroy', $service))->assertRedirect();
    expect(Service::find($service->id))->toBeNull();
});

test('invoice can be created with calculated totals and pdf can be downloaded', function () {
    $doctor = happySmileDoctor();
    $patient = Patient::where('clinic_id', $doctor->clinic_id)->whereDoesntHave('invoices')->first();

    $this->actingAs($doctor)->get('/invoices/create')->assertOk();

    $response = $this->actingAs($doctor)->post('/invoices', [
        'patient_id' => $patient->id,
        'patient_name' => $patient->name,
        'invoice_date' => now()->format('Y-m-d'),
        'items' => [
            ['service' => 'Tooth Cleaning', 'qty' => 1, 'price' => 800],
            ['service' => 'X-Ray', 'qty' => 2, 'price' => 250],
        ],
        'tax_pct' => 10,
        'discount_pct' => 5,
    ]);

    $invoice = Invoice::where('patient_id', $patient->id)->latest()->first();
    expect($invoice)->not->toBeNull();

    // subtotal = 800 + 500 = 1300; discount 5% = 65; taxable = 1235; tax 10% = 123.5; total = 1358.5
    expect((float) $invoice->subtotal)->toBe(1300.0);
    expect((float) $invoice->discount_amount)->toBe(65.0);
    expect((float) $invoice->tax_amount)->toBe(123.5);
    expect((float) $invoice->grand_total)->toBe(1358.5);
    expect($invoice->invoice_no)->toStartWith('INV');

    $response->assertRedirect(route('invoices.show', $invoice));

    $this->actingAs($doctor)->get(route('invoices.show', $invoice))->assertOk();

    $pdfResponse = $this->actingAs($doctor)->get(route('invoices.pdf', $invoice));
    $pdfResponse->assertOk();
    $pdfResponse->assertHeader('content-type', 'application/pdf');
});

test('invoice pdf is blocked on free plan without pdf feature', function () {
    $clinic = Clinic::create([
        'name' => 'Free Plan Clinic',
        'slug' => 'free-plan-clinic',
        'plan' => 'free',
        'status' => 'active',
    ]);

    $owner = User::create([
        'clinic_id' => $clinic->id,
        'name' => 'Free Owner',
        'email' => 'owner@freeplan.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    $invoice = Invoice::create([
        'clinic_id' => $clinic->id,
        'patient_name' => 'Walk-in Patient',
        'invoice_no' => 'INV001',
        'invoice_date' => now(),
        'items' => [['service' => 'Consultation', 'qty' => 1, 'price' => 500]],
        'subtotal' => 500,
        'tax_amount' => 0,
        'discount_amount' => 0,
        'grand_total' => 500,
        'status' => 'unpaid',
    ]);

    $this->actingAs($owner)->get(route('invoices.pdf', $invoice))->assertForbidden();
});

test('invoice status can be toggled between paid and unpaid', function () {
    $doctor = happySmileDoctor();
    $invoice = Invoice::where('clinic_id', $doctor->clinic_id)->where('status', 'unpaid')->first();

    $this->actingAs($doctor)->patch(route('invoices.status', $invoice))->assertRedirect();

    expect($invoice->fresh()->status)->toBe('paid');
});
