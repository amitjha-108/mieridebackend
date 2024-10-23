
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\Fund\FundController;



Route::middleware(['check.guard:admin'])->group(function () {
    Route::prefix('fund')->group(function () {

        Route::get('list', [FundController::class, 'listFunds']);
        Route::post('status', [FundController::class, 'fundStatus']);
        // Add more routes as needed
    });
});
