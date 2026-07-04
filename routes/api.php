<?php

use App\Http\Controllers\Api\AuditLogApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api-client', 'throttle:api-client'])->group(function () {
    Route::get('audit-logs', [AuditLogApiController::class, 'index'])
        ->middleware('ability:audit-logs:read');
});
