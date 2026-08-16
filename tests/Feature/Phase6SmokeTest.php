<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(fn () => $this->seed());

function happySmileDoctor4(): User
{
    return User::where('email', 'doctor@happysmile.com')->first();
}

// ---------- Public pages ----------

test('home page renders with SEO tags and JSON-LD', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Run Your Clinic');
    $response->assertSee('Request Free Access');
    $response->assertSee('og:title', false);
    $response->assertSee('SoftwareApplication');
    $response->assertSee('"@context"', false);
});

test('pricing page renders all four plans with whatsapp ctas', function () {
    $response = $this->get('/pricing');

    $response->assertOk();
    $response->assertSee('Simple, Transparent Pricing');
    $response->assertSee('Most Popular');
    $response->assertSee(urlencode('Hi, I am interested in DentaSaaS PREMIUM plan ₹799/month. Please help me get started.'), false);
    $response->assertSee('BreadcrumbList');
});

test('sitemap and robots static files exist with expected content', function () {
    // These are static files under public/, served directly by the web server
    // (not through Laravel's router), so we verify them on disk rather than via HTTP.
    expect(file_exists(public_path('sitemap.xml')))->toBeTrue();
    expect(file_get_contents(public_path('sitemap.xml')))->toContain('/pricing');

    expect(file_exists(public_path('robots.txt')))->toBeTrue();
    expect(file_get_contents(public_path('robots.txt')))->toContain('Disallow: /admin');
});

test('logged in user sees a dashboard link in the public navbar', function () {
    $doctor = happySmileDoctor4();

    $response = $this->actingAs($doctor)->get('/');

    $response->assertOk();
    $response->assertSee(route('dashboard'), false);
});

// ---------- Locale switching ----------

test('setting clinic language to hindi changes the app locale on subsequent requests', function () {
    $doctor = happySmileDoctor4();

    $this->actingAs($doctor)->put('/settings/language', ['language' => 'hi'])->assertRedirect();

    $response = $this->actingAs($doctor)->get('/dashboard');
    $response->assertOk();
    $response->assertSee('lang="hi"', false);
});

// ---------- SendAppointmentReminders command ----------

test('send-appointment-reminders notifies the doctor for confirmed appointments tomorrow only', function () {
    Notification::fake();

    $doctor = happySmileDoctor4();
    $clinic = $doctor->clinic;

    $tomorrowConfirmed = Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $doctor->id,
        'status' => 'confirmed',
        'appt_date' => today()->addDay(),
        'patient_name' => 'Tomorrow Confirmed Patient',
    ]);

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $doctor->id,
        'status' => 'pending',
        'appt_date' => today()->addDay(),
        'patient_name' => 'Tomorrow Pending Patient',
    ]);

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $doctor->id,
        'status' => 'confirmed',
        'appt_date' => today()->addDays(2),
        'patient_name' => 'Day After Patient',
    ]);

    $this->artisan('dentasaas:send-appointment-reminders')->assertSuccessful();

    Notification::assertSentTo(
        $doctor,
        AppointmentConfirmedNotification::class,
        fn ($notification) => $notification->appointment->id === $tomorrowConfirmed->id
    );

    Notification::assertSentToTimes($doctor, AppointmentConfirmedNotification::class, 1);
});

// ---------- Eager loading / N+1 correctness ----------

test('treatment plan completed count uses eager loaded sessions without extra queries', function () {
    $doctor = happySmileDoctor4();
    $clinic = $doctor->clinic;

    $plan = \App\Models\TreatmentPlan::create([
        'clinic_id' => $clinic->id, 'patient_name' => 'Query Count Patient', 'treatment' => 'Cleaning', 'total_sessions' => 3, 'status' => 'in_progress',
    ]);
    \App\Models\TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $plan->id, 'session_no' => 1, 'title' => 'Visit 1', 'status' => 'completed']);
    \App\Models\TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $plan->id, 'session_no' => 2, 'title' => 'Visit 2', 'status' => 'planned']);
    \App\Models\TreatmentSession::create(['clinic_id' => $clinic->id, 'plan_id' => $plan->id, 'session_no' => 3, 'title' => 'Visit 3', 'status' => 'planned']);

    $loaded = \App\Models\TreatmentPlan::with('sessions')->find($plan->id);

    \Illuminate\Support\Facades\DB::enableQueryLog();
    expect($loaded->completed_count)->toBe(1);
    expect($loaded->progress_pct)->toBe(33);
    $queries = \Illuminate\Support\Facades\DB::getQueryLog();
    \Illuminate\Support\Facades\DB::disableQueryLog();

    expect($queries)->toBeEmpty();
});

// ---------- Dashboard caching ----------

test('dashboard stats are cached and reused across calls', function () {
    $doctor = happySmileDoctor4();
    $clinic = $doctor->clinic;

    \Illuminate\Support\Facades\Cache::flush();

    $service = app(\App\Services\DashboardService::class);
    $first = $service->getStats($clinic);

    // Create a new patient after the first (cached) read — stats should stay stale until cache expires.
    \App\Models\Patient::factory()->create(['clinic_id' => $clinic->id]);

    $second = $service->getStats($clinic);

    expect($second['total_patients'])->toBe($first['total_patients']);
});
