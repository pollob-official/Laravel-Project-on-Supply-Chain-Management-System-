<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->integer('withholding_period_days')->nullable()->after('last_pesticide_date');
            $table->string('residue_risk_level', 20)->nullable()->after('withholding_period_days');
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn(['withholding_period_days', 'residue_risk_level']);
        });
    }
};

