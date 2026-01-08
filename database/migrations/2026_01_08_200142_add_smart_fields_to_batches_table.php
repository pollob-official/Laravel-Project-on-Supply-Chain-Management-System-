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
        $table->date('last_pesticide_date')->nullable()->after('expiry_date');
        $table->decimal('latitude', 10, 8)->nullable()->after('last_pesticide_date');
        $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        $table->string('quality_grade')->nullable()->after('longitude');
        $table->string('current_location')->nullable()->after('quality_grade');
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
