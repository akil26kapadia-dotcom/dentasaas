<?php

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(fn () => $this->seed());

test('doctor login redirects to tenant dashboard', function () {
    $doctor = User::where('email', 'doctor@happysmile.com')->first();

    $response = $this->post('/login', [
        'email' => $doctor->email,
        'password' => 'Doctor@2025',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('superadmin login redirects to admin dashboard', function () {
    $super = User::where('email', 'admin@dentasaas.com')->first();

    $response = $this->post('/login', [
        'email' => $super->email,
        'password' => 'Admin@2025',
    ]);

    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('dashboard renders for authenticated tenant user', function () {
    $doctor = User::where('email', 'doctor@happysmile.com')->first();

    $response = $this->actingAs($doctor)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('Dashboard');
});

test('patients index and create render for authenticated tenant user', function () {
    $doctor = User::where('email', 'doctor@happysmile.com')->first();

    $this->actingAs($doctor)->get('/patients')->assertOk();
    $this->actingAs($doctor)->get('/patients/create')->assertOk();
});

test('patient can be created and is scoped to clinic', function () {
    $doctor = User::where('email', 'doctor@happysmile.com')->first();

    $response = $this->actingAs($doctor)->post('/patients', [
        'name' => 'Smoke Test Patient',
        'phone' => '9999999999',
        'email' => 'smoke@example.com',
    ]);

    $patient = Patient::where('name', 'Smoke Test Patient')->first();

    expect($patient)->not->toBeNull();
    expect($patient->clinic_id)->toBe($doctor->clinic_id);
    $response->assertRedirect(route('patients.show', $patient));
});

test('patient creation is blocked once plan limit is reached', function () {
    $clinic = Clinic::create([
        'name' => 'Limit Test Clinic',
        'slug' => 'limit-test-clinic',
        'plan' => 'free',
        'status' => 'active',
    ]);

    $owner = User::create([
        'clinic_id' => $clinic->id,
        'name' => 'Limit Owner',
        'email' => 'owner@limittest.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    Patient::factory()->count(25)->create(['clinic_id' => $clinic->id]);

    $response = $this->actingAs($owner)->post('/patients', [
        'name' => 'One Too Many',
    ]);

    expect(Patient::where('clinic_id', $clinic->id)->count())->toBe(25);
    $response->assertSessionHasErrors('plan_limit');
});

test('doctor invite creates user and sends welcome mail', function () {
    Mail::fake();

    $doctor = User::where('email', 'doctor@happysmile.com')->first();

    $response = $this->actingAs($doctor)->post('/doctors', [
        'name' => 'New Colleague',
        'email' => 'new.colleague@happysmile.com',
        'role' => 'doctor',
        'specialty' => 'Orthodontics',
        'color' => '#1649FF',
    ]);

    $response->assertRedirect(route('doctors.index'));

    $created = User::where('email', 'new.colleague@happysmile.com')->first();
    expect($created)->not->toBeNull();
    expect($created->clinic_id)->toBe($doctor->clinic_id);

    Mail::assertSent(\App\Mail\WelcomeMail::class, fn ($mail) => $mail->hasTo('new.colleague@happysmile.com'));
});

test('access request submission sends mail and shows success', function () {
    Mail::fake();

    $response = $this->post('/request-access', [
        'name' => 'Prospect',
        'clinic_name' => 'Prospect Clinic',
        'email' => 'prospect@example.com',
    ]);

    $response->assertRedirect(route('request-access'));
    $response->assertSessionHas('success');

    Mail::assertSent(\App\Mail\AccessRequestMail::class);
});
