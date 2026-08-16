<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\TreatmentPlan;
use App\Models\TreatmentSession;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(fn () => $this->seed());

function happySmileDoctor2(): User
{
    return User::where('email', 'doctor@happysmile.com')->first();
}

// ---------- Prescriptions ----------

test('prescription can be created, listed and pdf downloaded', function () {
    $doctor = happySmileDoctor2();
    $patient = Patient::where('clinic_id', $doctor->clinic_id)->first();

    $this->actingAs($doctor)->get('/prescriptions')->assertOk();
    $this->actingAs($doctor)->get('/prescriptions/create')->assertOk();

    $response = $this->actingAs($doctor)->post('/prescriptions', [
        'patient_id' => $patient->id,
        'patient_name' => $patient->name,
        'doctor_name' => $doctor->name,
        'rx_date' => now()->format('Y-m-d'),
        'diagnosis' => 'Cavity',
        'medicines' => [
            ['name' => 'Amoxicillin', 'dose' => '500mg', 'freq' => 'TDS', 'duration' => '5 days', 'instructions' => 'After food'],
        ],
    ]);

    $response->assertRedirect(route('prescriptions.index'));

    $rx = Prescription::where('patient_id', $patient->id)->latest()->first();
    expect($rx)->not->toBeNull();
    expect($rx->medicines)->toHaveCount(1);
    expect($rx->clinic_id)->toBe($doctor->clinic_id);

    $pdfResponse = $this->actingAs($doctor)->get(route('prescriptions.pdf', $rx));
    $pdfResponse->assertOk();
    $pdfResponse->assertHeader('content-type', 'application/pdf');
});

test('prescription creation is blocked on plans without the prescriptions feature', function () {
    $clinic = Clinic::create([
        'name' => 'No Rx Clinic',
        'slug' => 'no-rx-clinic',
        'plan' => 'free',
        'status' => 'active',
    ]);

    $owner = User::create([
        'clinic_id' => $clinic->id,
        'name' => 'No Rx Owner',
        'email' => 'owner@norx.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($owner)->post('/prescriptions', [
        'patient_name' => 'Walk-in',
        'rx_date' => now()->format('Y-m-d'),
        'medicines' => [
            ['name' => 'Ibuprofen', 'dose' => '400mg', 'freq' => 'BD', 'duration' => '3 days'],
        ],
    ]);

    $response->assertSessionHasErrors('plan_limit');
    expect(Prescription::where('clinic_id', $clinic->id)->count())->toBe(0);
});

// ---------- Treatment Plans ----------

test('treatment plan creation auto-generates the correct number of sessions', function () {
    $doctor = happySmileDoctor2();
    $patient = Patient::where('clinic_id', $doctor->clinic_id)->first();

    $response = $this->actingAs($doctor)->post('/treatment-plans', [
        'patient_id' => $patient->id,
        'patient_name' => $patient->name,
        'doctor_name' => $doctor->name,
        'treatment' => 'Braces',
        'total_sessions' => 4,
        'status' => 'planned',
    ]);

    $response->assertRedirect(route('treatment-plans.index'));

    $plan = TreatmentPlan::where('treatment', 'Braces')->first();
    expect($plan)->not->toBeNull();
    expect($plan->sessions()->count())->toBe(4);
    expect($plan->sessions()->orderBy('session_no')->pluck('title')->all())
        ->toBe(['Visit 1', 'Visit 2', 'Visit 3', 'Visit 4']);
});

test('treatment plan show endpoint returns json with sessions and whatsapp urls', function () {
    $doctor = happySmileDoctor2();
    $plan = TreatmentPlan::where('clinic_id', $doctor->clinic_id)->first();

    $response = $this->actingAs($doctor)->getJson("/treatment-plans/{$plan->id}");

    $response->assertOk();
    $response->assertJsonStructure(['id', 'patient_name', 'sessions' => [['id', 'title', 'status']]]);
});

test('dragging a plan updates its status', function () {
    $doctor = happySmileDoctor2();
    $plan = TreatmentPlan::where('clinic_id', $doctor->clinic_id)->first();

    $response = $this->actingAs($doctor)->patchJson("/treatment-plans/{$plan->id}/drag", ['status' => 'in_progress']);

    $response->assertOk();
    expect($plan->fresh()->status)->toBe('in_progress');
});

test('completing all sessions auto-completes the plan via observer', function () {
    $doctor = happySmileDoctor2();
    $clinic = $doctor->clinic;

    $plan = TreatmentPlan::create([
        'clinic_id' => $clinic->id,
        'patient_name' => 'Observer Test Patient',
        'treatment' => 'Whitening',
        'total_sessions' => 2,
        'status' => 'in_progress',
    ]);

    $s1 = TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $plan->id, 'session_no' => 1, 'title' => 'Visit 1', 'status' => 'planned']);
    $s2 = TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $plan->id, 'session_no' => 2, 'title' => 'Visit 2', 'status' => 'planned']);

    $this->actingAs($doctor)->putJson("/treatment-plans/{$plan->id}/sessions/{$s1->id}", ['status' => 'completed'])->assertOk();
    expect($plan->fresh()->status)->toBe('in_progress');

    $this->actingAs($doctor)->putJson("/treatment-plans/{$plan->id}/sessions/{$s2->id}", ['status' => 'completed'])->assertOk();
    expect($plan->fresh()->status)->toBe('completed');
});

test('session belonging to another plan cannot be updated through a mismatched plan id', function () {
    $doctor = happySmileDoctor2();
    $clinic = $doctor->clinic;

    $planA = TreatmentPlan::create(['clinic_id' => $clinic->id, 'patient_name' => 'A', 'treatment' => 'X', 'total_sessions' => 1, 'status' => 'planned']);
    $planB = TreatmentPlan::create(['clinic_id' => $clinic->id, 'patient_name' => 'B', 'treatment' => 'Y', 'total_sessions' => 1, 'status' => 'planned']);
    $sessionOfA = TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $planA->id, 'session_no' => 1, 'title' => 'Visit 1', 'status' => 'planned']);

    $this->actingAs($doctor)->putJson("/treatment-plans/{$planB->id}/sessions/{$sessionOfA->id}", ['status' => 'completed'])
        ->assertNotFound();
});

test('treatment plan can be deleted and sessions cascade', function () {
    $doctor = happySmileDoctor2();
    $plan = TreatmentPlan::where('clinic_id', $doctor->clinic_id)->first();
    $sessionIds = $plan->sessions()->pluck('id');

    $this->actingAs($doctor)->delete("/treatment-plans/{$plan->id}")->assertRedirect(route('treatment-plans.index'));

    expect(TreatmentPlan::find($plan->id))->toBeNull();
    expect(TreatmentSession::whereIn('id', $sessionIds)->count())->toBe(0);
});

// ---------- Analytics ----------

test('analytics is blocked on free plan and redirects with a warning', function () {
    $clinic = Clinic::create(['name' => 'Free Analytics Clinic', 'slug' => 'free-analytics-clinic', 'plan' => 'free', 'status' => 'active']);
    $owner = User::create([
        'clinic_id' => $clinic->id, 'name' => 'Owner', 'email' => 'owner@freeanalytics.com',
        'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true, 'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($owner)->get('/analytics');

    $response->assertRedirect(route('dashboard'));
    $response->assertSessionHas('warning');
});

test('analytics renders full charts for premium plan', function () {
    $doctor = happySmileDoctor2(); // Happy Smile clinic is seeded on the premium plan

    $response = $this->actingAs($doctor)->get('/analytics');

    $response->assertOk();
    $response->assertSee('Top Services');
    $response->assertSee('Appointment Trend');
});

test('analytics shows only revenue chart for basic plan', function () {
    $clinic = Clinic::create(['name' => 'Basic Analytics Clinic', 'slug' => 'basic-analytics-clinic', 'plan' => 'basic', 'status' => 'active']);
    $owner = User::create([
        'clinic_id' => $clinic->id, 'name' => 'Owner', 'email' => 'owner@basicanalytics.com',
        'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true, 'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($owner)->get('/analytics');

    $response->assertOk();
    $response->assertDontSee('Top Services');
});

// ---------- Settings ----------

test('clinic settings can be updated', function () {
    $doctor = happySmileDoctor2();

    $this->actingAs($doctor)->get('/settings')->assertOk();

    $response = $this->actingAs($doctor)->put('/settings/clinic', [
        'name' => 'Happy Smile Updated',
        'phone' => '9999999999',
    ]);

    $response->assertRedirect(route('settings.index'));
    expect($doctor->clinic->fresh()->name)->toBe('Happy Smile Updated');
});

test('language can be switched', function () {
    $doctor = happySmileDoctor2();

    $this->actingAs($doctor)->put('/settings/language', ['language' => 'hi'])->assertRedirect(route('settings.index'));

    expect($doctor->clinic->fresh()->language)->toBe('hi');
});

test('logo can be uploaded and removed', function () {
    Storage::fake('public');

    $doctor = happySmileDoctor2();
    $file = \Illuminate\Http\UploadedFile::fake()->image('logo.png', 200, 200);

    $response = $this->actingAs($doctor)->post('/settings/logo', ['logo' => $file]);
    $response->assertOk();
    $response->assertJsonStructure(['logo_url']);

    $clinic = $doctor->clinic->fresh();
    expect($clinic->logo_path)->not->toBeNull();
    Storage::disk('public')->assertExists($clinic->logo_path);

    $removeResponse = $this->actingAs($doctor)->delete('/settings/logo');
    $removeResponse->assertOk();
    expect($doctor->clinic->fresh()->logo_path)->toBeNull();
});

test('password can be updated with correct current password', function () {
    $doctor = happySmileDoctor2();

    $response = $this->actingAs($doctor)->put('/settings/password', [
        'current_password' => 'Doctor@2025',
        'password' => 'NewPassword@123',
        'password_confirmation' => 'NewPassword@123',
    ]);

    $response->assertRedirect(route('settings.index'));
    $response->assertSessionHasNoErrors();
});

test('password update fails with wrong current password', function () {
    $doctor = happySmileDoctor2();

    $response = $this->actingAs($doctor)->put('/settings/password', [
        'current_password' => 'WrongPassword',
        'password' => 'NewPassword@123',
        'password_confirmation' => 'NewPassword@123',
    ]);

    $response->assertSessionHasErrors('current_password');
});
