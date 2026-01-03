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
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stakeholder_id');

            $table->string('trade_license')->nullable();
            $table->string('warehouse_location')->nullable();
            $table->integer('total_manpower')->default(0);
            $table->timestamps();

            $table->index('stakeholder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesalers');
    }
};
