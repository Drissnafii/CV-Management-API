<?php

use App\Http\Controllers\Api\JobOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/job-offers', [JobOfferController::class, 'index']);
