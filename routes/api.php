<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BatchController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
   return $request->user();
})->middleware('auth:sanctum');

Route::get("test-api", function(){
    return response()->json(["message" => "API is working fine"], 200);
});

// Public product trace API (read-only, no auth)
Route::get('batches/{batch_no}', [BatchController::class, 'show']);

// Auth endpoints (issue Sanctum tokens)
Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');

});


// Protected API (internal/admin apps)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::apiResource("user", UserController::class);
    Route::post('logout', [AuthController::class, 'logout']);
});
