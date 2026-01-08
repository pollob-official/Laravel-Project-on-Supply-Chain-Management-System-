<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EventTypeController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MillersSupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductJourneyController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\StakeholderController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WholesalerController;
use App\Http\Controllers\BatchController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- ১. Public & Auth Routes ---
Route::get('/', function () {
    return view("pages.erp.dashboard.index");
});

Auth::routes();

Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// পাবলিক ট্র্যাকিং (কিউআর কোড স্ক্যান করলে যা আসবে)
Route::get("trace/{tracking_no}", [ProductJourneyController::class, "public_trace"]);


// --- ২. Independent/Extra Routes (অ্যাডমিন গ্রুপের বাইরে) ---

// Student Routes
Route::get("/students", [StudentController::class, "index"]);
Route::get("/student/create", [StudentController::class, "create"]);
Route::get("/student/find/{id}", [StudentController::class, "find"]);
Route::get("/student/edit/{id}", [StudentController::class, "edit"]);
Route::get("/student/update/{id}/{name}", [StudentController::class, "update"]);
Route::delete("/student/delete/{id}", [StudentController::class, "delete"]);

// Event Type Routes
Route::get("/event_type", [EventTypeController::class, "index"]);
Route::get("/event_type/create", [EventTypeController::class, "create"]);
Route::post("/event_type/save", [EventTypeController::class, "save"]);
Route::get("/event_type/find/{id}", [EventTypeController::class, "find"]);
Route::get("/event_type/edit/{id}", [EventTypeController::class, "edit"]);
Route::put("/event_type/update/{id}", [EventTypeController::class, "update"]);
Route::delete("/event_type/delete/{id}", [EventTypeController::class, "delete"]);

// Customer Routes
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

// User Routes
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


// --- ৩. Supply Chain Admin Routes (গ্রুপ প্রিফিক্স সহ) ---
Route::prefix("admin")->group(function () {

    // Stakeholder Routes
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

    // Farmer Routes
    Route::prefix("farmer")->controller(FarmerController::class)->group(function(){
        Route::get("/", "index")->name('farmer.index');
        Route::get("create", "create")->name('farmer.create');
        Route::post("save", "save")->name('farmer.save');
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    // Wholesaler Routes
    Route::prefix("wholesaler")->controller(WholesalerController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::put("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    // Retailer Routes
    Route::prefix("retailer")->controller(RetailerController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    // Miller & Supplier Routes
    Route::prefix("miller")->controller(MillersSupplierController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    // Product Master
    Route::prefix("product")->controller(ProductController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    Route::prefix("category")->controller(CategoryController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
    });

    Route::prefix("unit")->controller(UnitController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
    });

    // Batch & QR Engine
    Route::prefix("batches")->controller(BatchController::class)->group(function(){
        Route::get("/", "index")->name('batches.index');
        Route::get("create", "create")->name('batches.create');
        Route::post("store", "store")->name('batches.store');
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed")->name('batches.trashed');
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

    // Journey / Handover
    Route::prefix("journey")->controller(ProductJourneyController::class)->group(function(){
        Route::get("/", "index");
        Route::get("create", "create");
        Route::post("save", "save");
        Route::get("edit/{id}", "edit");
        Route::post("update/{id}", "update");
        Route::delete("delete/{id}", "delete");
        Route::get("trashed", "trashed");
        Route::get("restore/{id}", "restore");
        Route::delete("force-delete/{id}", "force_delete");
    });

}); // End Admin Prefix Group


// --- ৪. Fallback ---
Route::fallback(function(){
    return "404 No Route matched";
});
