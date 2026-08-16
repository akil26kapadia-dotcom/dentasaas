<?php

use App\Mail\PlanExpiredMail;
use App\Mail\WelcomeMail;
use App\Models\AccessRequest;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use App\Notifications\AppointmentConfirmedNotification;
use App\Notifications\NewAccessRequestNotification;
use App\Notifications\PlanExpiryNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

beforeEach(fn () => $this->seed());

function superAdmin(): User
{
    return User::where('email', 'admin@dentasaas.com')->first();
}

function happySmileDoctor3(): User
{
    return User::where('email', 'doctor@happysmile.com')->first();
}

// ---------- Super admin access control ----------

test('regular clinic user cannot access admin routes', function () {
    $doctor = happySmileDoctor3();

    $this->actingAs($doctor)->get('/admin')->assertForbidden();
});

test('superadmin can access admin dashboard with full stats', function () {
    $admin = superAdmin();

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertOk();
    $response->assertSee('Total Clinics');
    $response->assertSee('Recent Clinics');
});

// ---------- Admin\ClinicController ----------

test('superadmin can create a clinic with an admin user and welcome mail is sent', function () {
    Mail::fake();
    $admin = superAdmin();

    $this->actingAs($admin)->get('/admin/clinics')->assertOk();

    $response = $this->actingAs($admin)->post('/admin/clinics', [
        'name' => 'Bright Smile Dental',
        'phone' => '9876543210',
        'email' => 'contact@brightsmile.com',
        'plan' => 'basic',
        'admin_name' => 'Bright Admin',
        'admin_email' => 'admin@brightsmile.com',
    ]);

    $response->assertRedirect(route('admin.clinics.index'));

    $clinic = Clinic::where('name', 'Bright Smile Dental')->first();
    expect($clinic)->not->toBeNull();
    expect($clinic->slug)->toBe('bright-smile-dental');
    expect($clinic->plan_expires_at)->not->toBeNull();

    $clinicAdmin = User::where('email', 'admin@brightsmile.com')->first();
    expect($clinicAdmin)->not->toBeNull();
    expect($clinicAdmin->clinic_id)->toBe($clinic->id);
    expect($clinicAdmin->role)->toBe('admin');

    Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo('admin@brightsmile.com'));
});

test('duplicate clinic name gets a unique slug suffix', function () {
    $admin = superAdmin();

    // "Happy Smile" already exists from the demo seeder with slug happy-smile.
    $this->actingAs($admin)->post('/admin/clinics', [
        'name' => 'Happy Smile',
        'plan' => 'free',
        'admin_name' => 'Second Admin',
        'admin_email' => 'second@happysmile-dup.com',
    ]);

    $second = Clinic::where('name', 'Happy Smile')->where('slug', '!=', 'happy-smile')->first();
    expect($second)->not->toBeNull();
    expect($second->slug)->toBe('happy-smile-2');
});

test('superadmin can update, extend, toggle status and reset password for a clinic', function () {
    Mail::fake();
    $admin = superAdmin();
    $clinic = Clinic::where('slug', 'happy-smile')->first();
    $clinic->update(['plan_expires_at' => today()->addDays(5)]);
    $originalExpiry = $clinic->plan_expires_at;

    $this->actingAs($admin)->put("/admin/clinics/{$clinic->id}", [
        'name' => 'Happy Smile Dental Care',
        'plan' => $clinic->plan,
        'status' => $clinic->status,
    ])->assertRedirect(route('admin.clinics.index'));
    expect($clinic->fresh()->name)->toBe('Happy Smile Dental Care');

    $this->actingAs($admin)->patch("/admin/clinics/{$clinic->id}/extend")->assertRedirect();
    $clinic->refresh();
    expect($clinic->plan_expires_at->isAfter($originalExpiry))->toBeTrue();

    $this->actingAs($admin)->patch("/admin/clinics/{$clinic->id}/status")->assertRedirect();
    expect($clinic->fresh()->status)->toBe('inactive');

    $this->actingAs($admin)->patch("/admin/clinics/{$clinic->id}/reset-password")->assertRedirect();
    Mail::assertSent(WelcomeMail::class);
});

test('setPlan updates plan and sets expiry 30 days out', function () {
    $admin = superAdmin();
    $clinic = Clinic::where('slug', 'happy-smile')->first();

    $this->actingAs($admin)->patch("/admin/clinics/{$clinic->id}/plan", ['plan' => 'deluxe'])->assertRedirect();

    $clinic->refresh();
    expect($clinic->plan)->toBe('deluxe');
    expect($clinic->plan_expires_at->isSameDay(today()->addDays(30)))->toBeTrue();
});

// ---------- Admin\AccessRequestController ----------

test('superadmin can approve an access request creating a clinic and sending welcome mail', function () {
    Mail::fake();
    $admin = superAdmin();

    $accessRequest = AccessRequest::create([
        'name' => 'Prospective Owner',
        'email' => 'owner@newclinic.com',
        'clinic_name' => 'New Sparkle Dental',
        'status' => 'pending',
    ]);

    $this->actingAs($admin)->get('/admin/access-requests')->assertOk();

    $response = $this->actingAs($admin)->patch("/admin/access-requests/{$accessRequest->id}/approve");
    $response->assertRedirect(route('admin.access-requests.index'));

    expect($accessRequest->fresh()->status)->toBe('approved');

    $clinic = Clinic::where('name', 'New Sparkle Dental')->first();
    expect($clinic)->not->toBeNull();
    expect($clinic->plan)->toBe('free');

    $newAdmin = User::where('email', 'owner@newclinic.com')->first();
    expect($newAdmin)->not->toBeNull();

    Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo('owner@newclinic.com'));
});

test('superadmin can deny an access request', function () {
    $admin = superAdmin();

    $accessRequest = AccessRequest::create([
        'name' => 'Rejected Owner', 'email' => 'rejected@example.com', 'clinic_name' => 'Rejected Clinic', 'status' => 'pending',
    ]);

    $this->actingAs($admin)->patch("/admin/access-requests/{$accessRequest->id}/deny")->assertRedirect();

    expect($accessRequest->fresh()->status)->toBe('denied');
    expect(Clinic::where('name', 'Rejected Clinic')->exists())->toBeFalse();
});

// ---------- Notifications ----------

test('new access request notifies all superadmins', function () {
    Notification::fake();

    $this->post('/request-access', [
        'name' => 'Notify Test',
        'clinic_name' => 'Notify Clinic',
        'email' => 'notify@example.com',
    ]);

    Notification::assertSentTo(superAdmin(), NewAccessRequestNotification::class);
});

test('confirming an appointment notifies the assigned doctor', function () {
    Notification::fake();
    $doctor = happySmileDoctor3();

    $appointment = Appointment::where('clinic_id', $doctor->clinic_id)->first();
    $appointment->update(['user_id' => $doctor->id, 'status' => 'pending']);

    $this->actingAs($doctor)->patchJson("/appointments/{$appointment->id}/status", ['status' => 'confirmed'])->assertOk();

    Notification::assertSentTo($doctor, AppointmentConfirmedNotification::class);
});

test('notifications index lists notifications and mark-read endpoints work', function () {
    $doctor = happySmileDoctor3();
    $appointment = Appointment::where('clinic_id', $doctor->clinic_id)->first();
    $appointment->update(['user_id' => $doctor->id]);

    $doctor->notify(new AppointmentConfirmedNotification($appointment));
    $notificationId = $doctor->fresh()->notifications()->first()->id;

    $this->actingAs($doctor)->get('/notifications')->assertOk()->assertSee('Appointment Confirmed');

    expect($doctor->fresh()->unreadNotifications()->count())->toBe(1);

    $this->actingAs($doctor)->patch("/notifications/{$notificationId}/read")->assertRedirect();
    expect($doctor->fresh()->unreadNotifications()->count())->toBe(0);
});

test('read-all marks every unread notification as read', function () {
    $doctor = happySmileDoctor3();
    $clinic = $doctor->clinic;

    $doctor->notify(new PlanExpiryNotification($clinic, 5));
    $doctor->notify(new PlanExpiryNotification($clinic, 3));

    expect($doctor->fresh()->unreadNotifications()->count())->toBe(2);

    $this->actingAs($doctor)->patch('/notifications/read-all')->assertRedirect();

    expect($doctor->fresh()->unreadNotifications()->count())->toBe(0);
});

// ---------- CheckPlanExpiry command ----------

test('check-plan-expiry command downgrades expired clinics and sends mail', function () {
    Mail::fake();

    $clinic = Clinic::create([
        'name' => 'Expired Plan Clinic',
        'slug' => 'expired-plan-clinic',
        'email' => 'contact@expiredplan.com',
        'plan' => 'premium',
        'plan_expires_at' => today()->subDay(),
        'status' => 'active',
    ]);

    $this->artisan('dentasaas:check-plan-expiry')->assertSuccessful();

    expect($clinic->fresh()->plan)->toBe('free');
    Mail::assertSent(PlanExpiredMail::class, fn ($mail) => $mail->hasTo('contact@expiredplan.com'));
});

test('check-plan-expiry command warns clinics expiring within 7 days', function () {
    Notification::fake();

    $clinic = Clinic::create([
        'name' => 'Soon Expiring Clinic',
        'slug' => 'soon-expiring-clinic',
        'plan' => 'basic',
        'plan_expires_at' => today()->addDays(3),
        'status' => 'active',
    ]);

    $clinicAdmin = User::create([
        'clinic_id' => $clinic->id, 'name' => 'Soon Admin', 'email' => 'soon@expiring.com',
        'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true, 'email_verified_at' => now(),
    ]);

    $this->artisan('dentasaas:check-plan-expiry')->assertSuccessful();

    expect($clinic->fresh()->plan)->toBe('basic');
    Notification::assertSentTo($clinicAdmin, PlanExpiryNotification::class);
});

// ---------- Error pages ----------

test('error views render directly', function () {
    $this->view('errors.403')->assertSee('Access Denied');
    $this->view('errors.404')->assertSee('Page Not Found');
    $this->view('errors.500')->assertSee('Something Went Wrong');
});
