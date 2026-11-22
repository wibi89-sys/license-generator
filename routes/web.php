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
        Route::get('/licenses', [LicenseController::class, 'index'])->name('licenses.index');
        Route::get('/licenses/create', [LicenseController::class, 'create'])->name('licenses.create');
        Route::post('/licenses', [LicenseController::class, 'store'])->name('licenses.store');
        Route::post('/licenses/{license}/reset-hwid', [LicenseController::class, 'resetHwid'])->name('licenses.reset-hwid');
    });
});
