<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LicenseApiController;
use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/license')->group(function () {
    Route::post('/activate', [LicenseApiController::class, 'activate']);
    Route::post('/refresh', [LicenseApiController::class, 'refresh']);
    Route::post('/heartbeat', [LicenseApiController::class, 'heartbeat']);
});

Route::prefix('v1/sync')->group(function () {
    Route::post('/push', [SyncController::class, 'push']);
    Route::get('/pull', [SyncController::class, 'pull']);
});
