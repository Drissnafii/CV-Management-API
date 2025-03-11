<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
