<?php

use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public
Route::get('/request-access', [AccessRequestController::class, 'create'])->name('request-access');
Route::post('/request-access', [AccessRequestController::class, 'store'])->name('request-access.store');

// Super admin
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

// Authenticated (clinic tenants)
Route::middleware(['auth', 'clinic.active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', PatientController::class);

    Route::resource('doctors', DoctorController::class)->except(['show', 'create', 'edit']);
    Route::patch('doctors/{doctor}/toggle', [DoctorController::class, 'toggleActive'])->name('doctors.toggle');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
