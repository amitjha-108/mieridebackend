<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DriverApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\QueryApiController;

require_once __DIR__ . '/api/user.php';
require_once __DIR__ . '/api/admin.php';

// Public routes
Route::post('/get-user-otp', [OtpApiController::class, 'sendUserOTP']);
Route::post('user-login', [UserApiController::class, 'loginWithOtporPassword']);

Route::post('/get-driver-register-otp', [OtpApiController::class, 'sendDriverRegisterOTP']);
Route::post('register-driver', [DriverApiController::class, 'registerDriver']);
Route::post('/get-driver-login-otp', [OtpApiController::class, 'sendDriverLoginOTP']);
Route::post('driver-login', [DriverApiController::class, 'driverLogin']);

Route::post('register-administrator', [AdminApiController::class, 'registerAdministrator']);
Route::post('login', [AdminApiController::class, 'login']);
Route::post('store-query', [QueryApiController::class, 'storeQueries']);


?>
