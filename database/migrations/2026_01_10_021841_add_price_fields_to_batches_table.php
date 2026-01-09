<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('batches', function (Blueprint $table) {
        $table->decimal('farmer_price', 10, 2)->nullable()->after('total_quantity'); // চাষীর থেকে কেনা দাম
        $table->decimal('processing_cost', 10, 2)->nullable()->after('farmer_price'); // প্রসেসিং খরচ
        $table->decimal('target_retail_price', 10, 2)->nullable()->after('processing_cost'); // খুচরা বাজার মূল্য
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            //
        });
    }
};
