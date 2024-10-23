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
use App\Http\Controllers\Api\SubroleUserApiController;
use App\Http\Controllers\Api\RideCategoryApiController;
use App\Http\Controllers\Api\PriceApiController;


// Public route for sending OTP
Route::post('/get-user-otp', [OtpApiController::class, 'sendUserOTP']);
Route::post('/get-driver-login-otp', [OtpApiController::class, 'sendDriverLoginOTP']);
Route::post('/get-driver-register-otp', [OtpApiController::class, 'sendDriverRegisterOTP']);
Route::post('user-login', [UserApiController::class, 'loginWithOtporPassword']);
Route::post('driver-login', [DriverApiController::class, 'driverLogin']);
Route::post('register-driver', [DriverApiController::class, 'registerDriver']);
Route::post('store-query', [QueryApiController::class, 'storeQueries']);

Route::post('register-administrator', [AdminApiController::class, 'registerAdministrator']);
// Route::post('login-administrator', [AdminApiController::class, 'loginAdministrator']);
// Route::post('login-subrole-user', [AdminApiController::class, 'loginSubroleUser']);

Route::post('login', [AdminApiController::class, 'login']);
Route::get('list-ride-category', [RideCategoryApiController::class, 'listRideCategories']);
Route::post('get-ride-price', [PriceApiController::class, 'getRidePrice']);




Route::middleware(['api'])->group(function () {
    Route::post('complete-user-profile', [UserApiController::class, 'completeUserProfile']);

});

Route::middleware(['check.guard:driver'])->group(function () {
    Route::post('complete-driver-profile', [DriverApiController::class, 'completeDriverProfile']);
    Route::post('edit-driver-profile', [DriverApiController::class, 'editDriver']);
    Route::get('get-approval-status', [DriverApiController::class, 'getApprovalStatus']);
});

Route::middleware(['check.guard:admin'])->group(function () {
    Route::post('create-admin', [AdminApiController::class, 'createAdmin']);
    Route::get('list-admins', [AdminApiController::class, 'listAdmins']);

    Route::post('store-ride-category', [RideCategoryApiController::class, 'storeRideCategory']);
    Route::post('edit-ride-category/{id}', [RideCategoryApiController::class, 'editRideCategory']);
    Route::delete('delete-ride-category/{id}', [RideCategoryApiController::class, 'deleteRideCategory']);
    Route::post('update-ride-category-status/{id}', [RideCategoryApiController::class, 'updateRideCategoryStatus']);

    Route::post('store-personal-ride-price', [PriceApiController::class, 'storePersonalRidePrice']);
    Route::post('edit-personal-ride-price', [PriceApiController::class, 'editPersonalRidePrice']);

    Route::post('store-sharing-ride-price', [PriceApiController::class, 'storeSharingRidePrice']);
    Route::post('edit-sharing-ride-price', [PriceApiController::class, 'editSharingRidePrice']);

    Route::post('store-test-drive-ride-price', [PriceApiController::class, 'storeTestdriveRidePrice']);
    Route::post('edit-test-drive-ride-price', [PriceApiController::class, 'editTestdriveRidePrice']);

    Route::post('get-ride-price-list', [PriceApiController::class, 'getRidePriceList']);
    Route::delete('delete-ride-price/{id}', [PriceApiController::class, 'deleteRidePriceById']);
});

Route::middleware(['check.guard:subroleuser'])->group(function () {

});

Route::middleware(['check.guard:subroleuser,admin'])->group(function () {
    Route::post('store-role', [RoleApiController::class, 'storeRoles']);
    Route::post('store-subrole', [RoleApiController::class, 'storeSubRoles']);
    Route::get('list-roles', [RoleApiController::class, 'listRoles']);
    Route::get('list-subroles/{roleId}', [RoleApiController::class, 'listSubroles']);
    Route::get('list-permissions-name', [PermissionApiController::class, 'permissionNamesList']);
    Route::get('list-role-with-permissions-name', [PermissionApiController::class, 'listRolesWithPermissions']);

    Route::post('create-subrole-user', [SubroleUserApiController::class, 'storeSubroleUser']);

    Route::get('driver/{id}', [DriverApiController::class, 'getDriverById']);
    Route::post('edit-driver/{id}', [DriverApiController::class, 'editDriverById']);
    Route::delete('delete-driver/{id}', [DriverApiController::class, 'deleteDriverById']);

    Route::get('/list-users', [UserApiController::class, 'listUsers']);
    Route::get('/list-drivers', [DriverApiController::class, 'listDrivers']);
    Route::get('list-queries', [QueryApiController::class, 'listQueries']);

    Route::post('assign-permission-to-role', [PermissionApiController::class, 'assignPermissionToRole']);
    Route::post('update-permission-to-role', [PermissionApiController::class, 'updatePermissionForRole']);

});

?>
