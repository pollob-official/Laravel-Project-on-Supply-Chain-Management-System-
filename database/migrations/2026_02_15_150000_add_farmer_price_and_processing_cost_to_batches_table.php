<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'farmer_price')) {
                $table->decimal('farmer_price', 10, 2)->nullable()->after('total_quantity');
            }
            if (!Schema::hasColumn('batches', 'processing_cost')) {
                $table->decimal('processing_cost', 10, 2)->nullable()->after('farmer_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'farmer_price')) {
                $table->dropColumn('farmer_price');
            }
            if (Schema::hasColumn('batches', 'processing_cost')) {
                $table->dropColumn('processing_cost');
            }
        });
    }
};

