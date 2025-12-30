<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeCotroller;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view ("pages.erp.dashboard.index");
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
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::delete("force-delete/{id}", "force_delete");
});

// Route::get("/customer", [CustomerController::class, "index"]);
// Route::get("/customer/create", [CustomerController::class, "create"]);
// Route::post("/customer/save", [CustomerController::class, "save"]);
// Route::delete("/customer/delete/{id}", [CustomerController::class, "delete"]);
// Route::get("/customer/edit/{id}", [CustomerController::class, "edit"]);
// Route::post("/customer/update/{id}", [CustomerController::class, "update"]);

Route::prefix("system")->group(function(){
    Route::resource('users', UserController::class);
});


Route::get("users/trashed", [UserController::class,"trashed"])->name("user.trashed");
Route::get("users/restore/{id}", [UserController::class,"restore"])->name("user.restore");
Route::delete("users/force-delete/{id}", [UserController::class,"force_delete"])->name("user.delete");

Route::fallback(function(){
    return "404 No Route matched";

});


// php artisan make:controller CustomerController.php
// user  ->  request  ->  controller -> model    -> database

Auth::routes();

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get("sendmail", function(){
     Mail::to("idbpollob@gmail.com")->send(new UserNotification);
           return "Mail has been sent successfully";
    });


