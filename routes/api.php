<?php

use App\Http\Controllers\Api\CVController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\JobApplicationController;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::post('auth/register', [AuthController::class, 'register']); // tested
Route::post('auth/login', [AuthController::class, 'login']); // tested
Route::get('/job-offers', [JobOfferController::class, 'index']); // tested
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']); // tested

// Test route
Route::get('/test', fn() => [
    "name" => "driss",
    "prenom" => "nafii",
    "age" => 8790
]);

// Protected routes - Changed from 'auth:sanctum' to 'auth.jwt'
Route::middleware('auth.jwt')->group(function () {
    // Auth routes
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // User route
    Route::get('/user', function(Request $request) {
        // Changed from $request->user() to Auth::guard('api')->user()
        return Auth::guard('api')->user();
    });

    // Job Offers routes
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);

    // CV routes
    Route::get('/cvs', [CVController::class, 'index']);
    Route::post('/cvs', [CVController::class, 'store']);
    Route::get('/cvs/{id}/download', [CVController::class, 'download']);
    Route::put('/cvs/{id}', [CVController::class, 'update']);
    Route::delete('/cvs/{id}', [CVController::class, 'destroy']);

    // Job Application routes
    Route::post('/applications', [JobApplicationController::class, 'store']);

    // Skills

    Route::post('users/{user}/skills', [SkillController::class, 'attachSkill']);
    Route::delete('users/{user}/skills', [SkillController::class, 'detachSkill']);
    Route::put('users/{user}/skills', [SkillController::class, 'syncSkills']);
});
