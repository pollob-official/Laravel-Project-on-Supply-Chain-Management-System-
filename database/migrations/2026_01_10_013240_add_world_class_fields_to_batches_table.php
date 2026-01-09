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
        Schema::table('batches', function (Blueprint $blueprint) {
            // ১. কস্ট ও ইকোনমিক ডাটা (Profit Analysis এর জন্য)
            $blueprint->decimal('production_cost_per_unit', 15, 2)->nullable()->after('total_quantity');
            $blueprint->string('currency', 10)->default('BDT')->after('production_cost_per_unit');

            // ২. এনভায়রনমেন্ট ও কমপ্লায়েন্স (Global Standard এর জন্য)
            $blueprint->enum('certification_type', ['Standard', 'Organic', 'GAP', 'ISO', 'Non-GMO'])->default('Standard')->after('quality_grade');
            $blueprint->string('storage_condition')->nullable()->comment('Temp, Humidity info')->after('current_location');
            $blueprint->string('water_footprint')->nullable()->after('storage_condition');

            // ৩. স্মার্ট স্কোরিং (AI Analysis এর জন্য)
            $blueprint->integer('safety_score')->default(100)->after('qc_status');
            $blueprint->string('target_market')->nullable()->after('certification_type');

            // ৪. অ্যাডিশনাল অডিট ট্রেইল
            $blueprint->string('inspector_id')->nullable()->after('qc_officer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'production_cost_per_unit',
                'currency',
                'certification_type',
                'storage_condition',
                'water_footprint',
                'safety_score',
                'target_market',
                'inspector_id'
            ]);
        });
    }
};
