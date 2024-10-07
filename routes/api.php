<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\DriverApiController;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\QueryApiController;
use App\Http\Controllers\Api\RoleApiController;
use App\Http\Controllers\Api\PermissionApiController;


// Public route for sending OTP
Route::post('/get-user-otp', [OtpApiController::class, 'sendUserOTP']);
Route::post('/get-driver-login-otp', [OtpApiController::class, 'sendDriverLoginOTP']);
Route::post('/get-driver-register-otp', [OtpApiController::class, 'sendDriverRegisterOTP']);
Route::post('user-login', [UserApiController::class, 'loginWithOtporPassword']);
Route::post('driver-login', [DriverApiController::class, 'driverLogin']);
Route::post('register-driver', [DriverApiController::class, 'registerDriver']);

Route::post('register-administrator', [AdminApiController::class, 'registerAdministrator']);
Route::post('login-administrator', [AdminApiController::class, 'loginAdministrator']);

Route::post('store-query', [QueryApiController::class, 'storeQueries']);



Route::middleware(['auth:api'])->group(function () {
    Route::post('complete-user-profile', [UserApiController::class, 'completeUserProfile']);

});

Route::middleware(['auth:driver'])->group(function () {
    Route::post('complete-driver-profile', [DriverApiController::class, 'completeDriverProfile']);
    Route::post('edit-driver-profile', [DriverApiController::class, 'editDriver']);
    Route::get('get-approval-status', [DriverApiController::class, 'getApprovalStatus']);
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/list-users', [UserApiController::class, 'listUsers']);
    Route::get('/list-drivers', [DriverApiController::class, 'listDrivers']);
    Route::delete('/delete-driver/{id}', [DriverApiController::class, 'deleteDriver']);
    Route::get('list-queries', [QueryApiController::class, 'listQueries']);

    Route::post('store-role', [RoleApiController::class, 'storeRoles']);
    Route::get('list-roles', [RoleApiController::class, 'listRoles']);
    Route::post('assign-permission-to-role', [PermissionApiController::class, 'assignPermissionToRole']);
    Route::post('update-permission-to-role', [PermissionApiController::class, 'updatePermissionForRole']);
});

?>
