<?php

use App\Http\Controllers\Api\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobApplicationController;

// Public routes
Route::post('/register', [AuthController::class, 'register']); // tested
Route::post('/login', [AuthController::class, 'login']); // tested
Route::get('/job-offers', [JobOfferController::class, 'index']); // working
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']); // working

// Test route
Route::get('/test', fn() => [
    "name" => "driss",
    "prenom" => "nafii",
    "age" => 8790
]);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User route
    Route::get('/user', fn(Request $request) => $request->user()); // working

    // Job Offers routes
    Route::post('/job-offers', [JobOfferController::class, 'store']); // working
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']); // working | err: canot update a foreignkey ... (this should be handeled next time)
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']); // working

    // CV routes
    Route::get('/cvs', [CVController::class, 'index']); // working
    Route::post('/cvs', [CVController::class, 'store']); // success in the local storage of laravel
    Route::get('/cvs/{id}/download', [CVController::class, 'download']); // sucess and fixed
    Route::put('/cvs/{id}', [CVController::class, 'update']); // working
    Route::delete('/cvs/{id}', [CVController::class, 'destroy']);

    // Job Application routes
    Route::post('/applications', [JobApplicationController::class, 'store']);

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout']);
});
