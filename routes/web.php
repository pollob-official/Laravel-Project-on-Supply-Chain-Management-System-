<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "welcome to Laravel World";
});

Route::get('/pollob', function () {
    return "Hello Pollob";
});

// Route::get('/students', function () {
//     return view ("students");
// });

// Route::get('/student/{name}/{id}', function ($name, $id) {
//   $student=["Hasan", "Masud", "M A Jalil"];
//     return view ("students", ["id"=>$id, "name"=>$name]);
// });


Route::get("/students", [StudentController::class, "index"]);
Route::get("/student/create", [StudentController::class, "create"]);
Route::get("/student/find/{id}", [StudentController::class, "find"]);
Route::get("/student/edit/{id}", [StudentController::class, "edit"]);
Route::get("/student/update/{id}/{name}", [StudentController::class, "update"]);
Route::get("/student/delete/{id}", [StudentController::class, "delete"]);

// Event_Type:

Route::get("/event_type", [EventTypeController::class, "index"]);
Route::get("/event_type/create", [EventTypeController::class, "create"]);
Route::post("/event_type/save", [EventTypeController::class, "save"]);
// Route::get("/event_type/find/{id}", [EventTypeController::class, "find"]);
Route::get("/event_type/edit/{id}", [EventTypeController::class, "edit"]);
Route::put("/event_type/update/{id}", [EventTypeController::class, "update"]);
Route::delete("/event_type/delete/{id}", [EventTypeController::class, "delete"]);



Route::prefix("customer")->controller(CustomerController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::delete("delete/{id}", "delete");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
});

// Route::get("/customer", [CustomerController::class, "index"]);
// Route::get("/customer/create", [CustomerController::class, "create"]);
// Route::post("/customer/save", [CustomerController::class, "save"]);
// Route::delete("/customer/delete/{id}", [CustomerController::class, "delete"]);
// Route::get("/customer/edit/{id}", [CustomerController::class, "edit"]);
// Route::post("/customer/update/{id}", [CustomerController::class, "update"]);

Route::fallback(function(){
    return "404 No Route matched";

});


// php artisan make:controller CustomerController.php
// user  ->  request  ->  controller -> model    -> database
