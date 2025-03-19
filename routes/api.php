<?php

use App\Http\Controllers\Api\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobApplicationController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/job-offers', [JobOfferController::class, 'index']);
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']);

// Test route
Route::get('/test', fn() => [
    "name" => "ikdsb",
    "prenom" => "ikdsb",
    "age" => 8790
]);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User route
    Route::get('/user', fn(Request $request) => $request->user());

    // Job Offers routes
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);

    // CV routes
    Route::post('/cvs', [CVController::class, 'store']);
    Route::get('/cvs/{id}/download', [CVController::class, 'download']);

    // Job Application routes
    Route::post('/applications', [JobApplicationController::class, 'store']);

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout']);
});
