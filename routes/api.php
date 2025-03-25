<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\Api\CVController;
use App\Http\Controllers\Api\JobOfferController;
use App\Http\Controllers\Api\JobApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*------------------------------------------
| Public Routes
|------------------------------------------*/
Route::post('auth/register', [AuthController::class, 'register']); // tested
Route::post('auth/login', [AuthController::class, 'login']); // tested

// Job Offers
Route::get('/job-offers', [JobOfferController::class, 'index']); // tested
Route::get('/job-offers/{id}', [JobOfferController::class, 'show']); // tested

// Test route
Route::get('/test', fn() => [
    'name' => 'driss',
    'prenom' => 'nafii',
    'age' => 8790
]);

/*------------------------------------------
| Protected Routes (Require Authentication)
| Middleware: auth:api (JWT)
|------------------------------------------*/
Route::middleware('auth:api')->group(function () {
    /* Auth Routes */
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/me', [AuthController::class, 'me']);

    /* User Routes */
    Route::get('/user', function(Request $request) {
        return Auth::guard('api')->user();
    });

    /* Job Offer Routes */
    Route::post('/job-offers', [JobOfferController::class, 'store']);
    Route::put('/job-offers/{id}', [JobOfferController::class, 'update']);
    Route::delete('/job-offers/{id}', [JobOfferController::class, 'destroy']);

    /* CV Routes */
    Route::get('/cvs', [CVController::class, 'index']);
    Route::post('/cvs', [CVController::class, 'store']);
    Route::get('/cvs/{id}/download', [CVController::class, 'download']);
    Route::put('/cvs/{id}', [CVController::class, 'update']);
    Route::delete('/cvs/{id}', [CVController::class, 'destroy']);

    /* Job Application Routes */
    Route::post('/applications', [JobApplicationController::class, 'store']);

    /* Skill Routes */
    Route::post('users/{user}/skills', [SkillController::class, 'attachSkill']); // tested
    Route::delete('users/{user}/skills', [SkillController::class, 'detachSkill']); // tested
    Route::put('users/{user}/skills', [SkillController::class, 'syncSkills']); // Demo ! Not tested
});
