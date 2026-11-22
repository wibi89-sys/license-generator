<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LicenseApiController;
use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

// Support both singular ('license') and plural ('licenses') paths, with and without 'v1'
foreach (['v1/license', 'v1/licenses', 'license', 'licenses'] as $prefix) {
    Route::prefix($prefix)->group(function () {
        Route::post('/activate', [LicenseApiController::class, 'activate']);
        Route::post('/refresh', [LicenseApiController::class, 'refresh']);
        Route::post('/heartbeat', [LicenseApiController::class, 'heartbeat']);
        Route::get('/public-key', [LicenseApiController::class, 'publicKey']);
    });
}

Route::prefix('v1/sync')->group(function () {
    Route::post('/push', [SyncController::class, 'push']);
    Route::get('/pull', [SyncController::class, 'pull']);
});
