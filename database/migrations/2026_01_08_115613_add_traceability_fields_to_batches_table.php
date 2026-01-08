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
        // ১. Seed & Farming Data
        $table->string('seed_brand')->after('initial_farmer_id')->nullable();
        $table->string('seed_variety')->after('seed_brand')->nullable();
        $table->date('sowing_date')->after('seed_variety')->nullable(); // বীজ বপনের তারিখ

        // ২. Input Logs (কীটনাশক ও সার)
        $table->text('pesticide_history')->after('sowing_date')->nullable(); // কি কি কীটনাশক কতবার দিয়েছে
        $table->text('fertilizer_history')->after('pesticide_history')->nullable(); // সারের বিবরণ

        // ৩. Quality & Harvest
        $table->date('harvest_date')->after('manufacturing_date')->nullable(); // ফসল তোলার আসল তারিখ
        $table->string('moisture_level')->after('harvest_date')->nullable(); // আর্দ্রতা (শস্যের জন্য জরুরি)

        // ৪. QC Approval System
        $table->enum('qc_status', ['pending', 'approved', 'rejected'])->default('pending')->after('qr_code');
        $table->string('qc_officer_name')->after('qc_status')->nullable();
        $table->text('qc_remarks')->after('qc_officer_name')->nullable();
    });
}

public function down()
{
    Schema::table('batches', function (Blueprint $table) {
        $table->dropColumn([
            'seed_brand', 'seed_variety', 'sowing_date',
            'pesticide_history', 'fertilizer_history',
            'harvest_date', 'moisture_level',
            'qc_status', 'qc_officer_name', 'qc_remarks'
        ]);
    });
}
};
