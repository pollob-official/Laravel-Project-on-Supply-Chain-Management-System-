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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            // Manual Link: unsignedBigInteger ব্যবহার করা হয়েছে
            $table->unsignedBigInteger('stakeholder_id')->comment('Link to stakeholders table id');

            $table->string('land_area')->nullable();
            $table->string('farmer_card_no')->nullable();
            $table->text('crop_history')->nullable();
            $table->timestamps();

            // দ্রুত ডাটা খোঁজার জন্য ইনডেক্স
            $table->index('stakeholder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
