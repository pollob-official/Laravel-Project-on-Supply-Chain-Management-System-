<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MillersSupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\StakeholderController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WholesalerController;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view ("pages.erp.dashboard.index");
});



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
Route::get("/event_type/find/{id}", [EventTypeController::class, "find"]);
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

Route::prefix("user")->controller(UserController::class)->group(function(){
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


Route::fallback(function(){
    return "404 No Route matched";

});


// php artisan make:controller CustomerController.php
// user  ->  request  ->  controller -> model    -> database

Auth::routes();

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route::get("sendmail", function(){
//      Mail::to("idbpollob@gmail.com")->send(new UserNotification("Pollob"));
//            return "Mail has been sent successfully";
//     });

Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// Route::resource('stakeholder', StakeholderController::class);

Route::prefix("stakeholder")->controller(StakeholderController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::post("save", "save");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::delete("force-delete/{id}", "force_delete");
});

Route::prefix("farmer")->controller(FarmerController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::delete("force-delete/{id}", "force_delete");
});


// ২. Wholesaler Routes
Route::prefix("wholesaler")->controller(WholesalerController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::put("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::get("force-delete/{id}", "force_delete");
});

// ৩. Retailer Routes
Route::prefix("retailer")->controller(RetailerController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::get("force-delete/{id}", "force_delete");
});

// ৪. Millers & Supplier Routes
Route::prefix("miller")->controller(MillersSupplierController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::get("force-delete/{id}", "force_delete");
});

Route::prefix("product")->controller(ProductController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
    Route::get("trashed", "trashed");
    Route::get("restore/{id}", "restore");
    Route::get("force-delete/{id}", "force_delete");
});

Route::prefix("category")->controller(CategoryController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
});

Route::prefix("unit")->controller(UnitController::class)->group(function(){
    Route::get("/", "index");
    Route::get("create", "create");
    Route::post("save", "save");
    Route::post("store", "store");
    Route::get("edit/{id}", "edit");
    Route::post("update/{id}", "update");
    Route::delete("delete/{id}", "delete");
});
