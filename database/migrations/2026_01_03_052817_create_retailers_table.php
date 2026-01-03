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
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stakeholder_id');

            $table->string('shop_name')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('market_name')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('stakeholder_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailers');
    }
};
