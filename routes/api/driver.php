<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DriverApiController;

Route::middleware(['check.guard:driver'])->group(function () {
    Route::post('complete-driver-profile', [DriverApiController::class, 'completeDriverProfile']);
    Route::post('edit-driver-profile', [DriverApiController::class, 'editDriver']);
    Route::get('get-approval-status', [DriverApiController::class, 'getApprovalStatus']);
});


