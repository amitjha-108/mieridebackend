<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;




Route::middleware(['check.guard:user'])->group(function () {
    Route::post('complete-user-profile', [UserApiController::class, 'completeUserProfile']);
    Route::post('add-cash', [UserApiController::class, 'fundPost']);
    
});
