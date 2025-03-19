<?php

use App\Http\Controllers\Api\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobApplicationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/test' , function(){
    return [
        "name" => "ikdsb",
        "prenom" => "ikdsb",
        "age" => 8790
    ];
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/job-offers', [JobOfferController::class, 'index']);
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Job Offers routes
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cvs', [App\Http\Controllers\Api\CVController::class, 'store']);
    Route::get('/cvs/{id}/download', [App\Http\Controllers\Api\CVController::class, 'download']);
    Route::post('/applications', [JobApplicationController::class, 'store']);
});


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/job-offers', [JobOfferController::class, 'index']);
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']);

// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
    // Job Offers routes
    // Route::post('/job-offers', [JobOfferController::class, 'store']);
    // Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    // Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);

    // CV routes
    // Route::get('/cvs', [CVController::class, 'index']);
    // Route::post('/cvs', [CVController::class, 'store']);
    // Route::get('/cvs/{id}', [CVController::class, 'show']);
    // Route::delete('/cvs/{id}', [CVController::class, 'destroy']);
    // Route::get('/cvs/{id}/download', [CVController::class, 'download']);

    // Job Application routes
    // Route::get('/applications', [JobApplicationController::class, 'index']);
    // Route::post('/applications', [JobApplicationController::class, 'store']);
    // Route::get('/applications/{id}', [JobApplicationController::class, 'show']);
    // Route::delete('/applications/{id}', [JobApplicationController::class, 'destroy']);
    // Route::post('/applications/batch', [JobApplicationController::class, 'batchApply']);

    // Logout route
    // Route::post('/logout', [AuthController::class, 'logout']);
// });

