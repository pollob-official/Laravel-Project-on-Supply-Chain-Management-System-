<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // যদি আগের টেবিল থাকে তবে তা ড্রপ করবে, তারপর নতুন করে তৈরি করবে
        Schema::dropIfExists('batches');

        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no'); // Unique বাদ দেওয়া হয়েছে
            $table->bigInteger('product_id')->nullable(); // Foreign Key constraint বাদ দেওয়া হয়েছে

            // --- Dynamic Source Logic ---
            $table->string('source_type')->default('farmer');
            $table->bigInteger('source_id')->nullable();

            // --- Location Transparency ---
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('manual_address')->nullable();
            $table->string('current_location')->nullable();

            // --- Cultivation Data (For Farmers) ---
            $table->string('seed_brand')->nullable();
            $table->string('seed_variety')->nullable();
            $table->date('sowing_date')->nullable();
            $table->date('harvest_date')->nullable();
            $table->date('last_pesticide_date')->nullable();
            $table->text('pesticide_history')->nullable();
            $table->text('fertilizer_history')->nullable();

            // --- Processing Data (For Millers/Suppliers) ---
            $table->string('processing_method')->nullable();
            $table->string('raw_material_source_batch')->nullable();

            // --- Quantity & Units ---
            $table->decimal('total_quantity', 15, 2)->default(0);
            $table->string('unit_name')->default('KG');

            // --- Financial Transparency (Per Unit) ---
            $table->decimal('buying_price_per_unit', 15, 2)->default(0);
            $table->decimal('processing_cost_per_unit', 15, 2)->default(0);
            $table->decimal('target_retail_price', 15, 2)->default(0);
            $table->string('currency')->default('BDT');

            // --- Quality & Safety ---
            $table->string('qc_status')->default('pending'); // Enum এর বদলে string
            $table->integer('safety_score')->default(100);
            $table->string('quality_grade')->nullable();
            $table->string('moisture_level')->nullable();
            $table->string('certification_type')->default('Standard');

            // --- Dates ---
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();

            // --- Marketing & Others ---
            $table->string('qr_code')->nullable();
            $table->string('target_market')->nullable();
            $table->string('storage_condition')->nullable();
            $table->string('water_footprint')->nullable();

            $table->string('qc_officer_name')->nullable();
            $table->text('qc_remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('batches');
    }
};
