<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Guest routes (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Full CRUD License routes
        Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
        Route::get('/licenses/create', [LicenseController::class, 'create'])->name('licenses.create');
        Route::post('/licenses', [LicenseController::class, 'store'])->name('licenses.store');
        Route::get('/licenses/{license}', [LicenseController::class, 'show'])->name('licenses.show');
        Route::get('/licenses/{license}/edit', [LicenseController::class, 'edit'])->name('licenses.edit');
        Route::put('/licenses/{license}', [LicenseController::class, 'update'])->name('licenses.update');
        Route::delete('/licenses/{license}', [LicenseController::class, 'destroy'])->name('licenses.destroy');
        
        // License Actions
        Route::post('/licenses/{license}/reset-hwid', [LicenseController::class, 'resetHwid'])->name('licenses.reset-hwid');
        Route::post('/licenses/{license}/toggle-status', [LicenseController::class, 'toggleStatus'])->name('licenses.toggle-status');

        // Application Settings routes
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/regenerate-token', [\App\Http\Controllers\Admin\SettingController::class, 'regenerateApiToken'])->name('settings.regenerate-token');
    });
});
