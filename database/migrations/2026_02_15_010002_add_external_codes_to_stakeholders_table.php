<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->string('external_code')->nullable()->after('nid');
            $table->string('gln')->nullable()->after('external_code');
        });
    }

    public function down(): void
    {
        Schema::table('stakeholders', function (Blueprint $table) {
            $table->dropColumn(['external_code', 'gln']);
        });
    }
};

