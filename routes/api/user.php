<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PriceApiController;
use App\Http\Controllers\Api\RideCategoryApiController;




Route::middleware(['check.guard:user'])->group(function () {
    Route::post('complete-user-profile', [UserApiController::class, 'completeUserProfile']);
    Route::post('add-cash', [UserApiController::class, 'fundPost']);

    //for price and city work
    Route::get('list-ride-category', [RideCategoryApiController::class, 'listRideCategories']);
    Route::post('get-ride-price', [PriceApiController::class, 'getRidePrice']);

});
