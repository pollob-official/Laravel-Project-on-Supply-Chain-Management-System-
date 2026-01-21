<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
   return $request->user();
})->middleware('auth:sanctum');

Route::get("test-api", function(){
    return response()->json(["message" => "API is working fine"], 200);
});

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');

});


Route::middleware('auth:sanctum')->group( function () {
   Route::apiResource("customer", CustomerController::class);
   Route::apiResource("user", UserController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});

 Route::apiResource("customer", CustomerController::class);


