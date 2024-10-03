<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DriverApiController;


// Public route for sending OTP
Route::post('/get-user-otp', [OtpApiController::class, 'sendUserOTP']);
Route::post('/get-driver-login-otp', [OtpApiController::class, 'sendDriverLoginOTP']);
Route::post('/get-driver-register-otp', [OtpApiController::class, 'sendDriverRegisterOTP']);
Route::post('user-login', [UserApiController::class, 'loginWithOtporPassword']);
Route::post('driver-login', [DriverApiController::class, 'driverLogin']);
Route::post('register-driver', [DriverApiController::class, 'registerDriver']);



Route::middleware(['auth:api'])->group(function () {
    Route::post('complete-user-profile', [UserApiController::class, 'completeUserProfile']);

});

Route::middleware(['auth:driver'])->group(function () {
    Route::post('complete-driver-profile', [DriverApiController::class, 'completeDriverProfile']);
    Route::post('edit-driver-profile', [DriverApiController::class, 'editDriver']);
});

Route::delete('/delete-driver/{id}', [DriverApiController::class, 'deleteDriver']);
Route::get('/list-drivers', [DriverApiController::class, 'listDrivers']);

?>
