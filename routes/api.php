<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Profile routes
    Route::get('/profile', [UserProfileController::class, 'show']);
    Route::put('/profile', [UserProfileController::class, 'update']);
    
    // Car routes
    Route::apiResource('cars', CarController::class);
    
    // Bid routes
    Route::apiResource('bids', BidController::class);
    
    // Rental routes
    Route::apiResource('rentals', RentalController::class);
});

// Public routes
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{car}', [CarController::class, 'show']);
