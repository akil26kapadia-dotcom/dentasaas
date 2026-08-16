<?php

use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\Admin\AccessRequestController as AdminAccessRequestController;
use App\Http\Controllers\Admin\ClinicController as AdminClinicController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TreatmentPlanController;
use App\Http\Controllers\TreatmentSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public
Route::get('/request-access', [AccessRequestController::class, 'create'])->name('request-access');
Route::post('/request-access', [AccessRequestController::class, 'store'])->name('request-access.store');

// Super admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('clinics', AdminClinicController::class);
    Route::patch('clinics/{clinic}/plan', [AdminClinicController::class, 'setPlan'])->name('clinics.plan');
    Route::patch('clinics/{clinic}/extend', [AdminClinicController::class, 'extendPlan'])->name('clinics.extend');
    Route::patch('clinics/{clinic}/status', [AdminClinicController::class, 'toggleStatus'])->name('clinics.status');
    Route::patch('clinics/{clinic}/reset-password', [AdminClinicController::class, 'resetPassword'])->name('clinics.reset-password');

    Route::get('access-requests', [AdminAccessRequestController::class, 'index'])->name('access-requests.index');
    Route::patch('access-requests/{accessRequest}/approve', [AdminAccessRequestController::class, 'approve'])->name('access-requests.approve');
    Route::patch('access-requests/{accessRequest}/deny', [AdminAccessRequestController::class, 'deny'])->name('access-requests.deny');
});

// Authenticated (clinic tenants)
Route::middleware(['auth', 'clinic.active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', PatientController::class);

    Route::resource('doctors', DoctorController::class)->except(['show', 'create', 'edit']);
    Route::patch('doctors/{doctor}/toggle', [DoctorController::class, 'toggleActive'])->name('doctors.toggle');

    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

    Route::resource('services', ServiceController::class)->except(['show', 'create', 'edit']);
    Route::patch('services/{service}/toggle', [ServiceController::class, 'toggleActive'])->name('services.toggle');

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    Route::resource('prescriptions', PrescriptionController::class)->except(['edit', 'update', 'show']);
    Route::get('prescriptions/{prescription}/pdf', [PrescriptionController::class, 'pdf'])->name('prescriptions.pdf');

    Route::resource('treatment-plans', TreatmentPlanController::class)->parameters(['treatment-plans' => 'plan']);
    Route::patch('treatment-plans/{plan}/drag', [TreatmentPlanController::class, 'drag'])->name('treatment-plans.drag');
    Route::put('treatment-plans/{plan}/sessions/{session}', [TreatmentSessionController::class, 'update'])->name('treatment-sessions.update');

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/clinic', [SettingsController::class, 'updateClinic'])->name('settings.clinic');
    Route::post('settings/logo', [SettingsController::class, 'uploadLogo'])->name('settings.logo.upload');
    Route::delete('settings/logo', [SettingsController::class, 'removeLogo'])->name('settings.logo.remove');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('settings/language', [SettingsController::class, 'updateLanguage'])->name('settings.language');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
