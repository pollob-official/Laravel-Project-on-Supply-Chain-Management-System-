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
        Schema::create('millers_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stakeholder_id');

            $table->string('factory_license')->nullable();
            $table->string('milling_capacity')->nullable();
            $table->string('storage_unit_type')->nullable(); 
            $table->timestamps();

            $table->index('stakeholder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('millers_suppliers');
    }
};
