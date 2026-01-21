<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    Route::apiResource("user", UserController::class);
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
});


Route::middleware('auth:sanctum')->group( function () {
   Route::apiResource("customer", CustomerController::class);
});

 Route::apiResource("customer", CustomerController::class);
  Route::apiResource("user", UserController::class);

