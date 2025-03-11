<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\AuthController;


//public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'register']);
Route::get('/job-offers', [JobOfferController::class, 'index']);
Route::get('/gob-offers/{$id}', [JobOfferController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function ()  {
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::post('/job-offers/{$id}', [JobOfferController::class, 'update']);
    Route::post('/job-offers/{$id}', [JobOfferController::class, 'destroy']);

    Route::post('logout', [AuthController::class, 'logout']);
});
