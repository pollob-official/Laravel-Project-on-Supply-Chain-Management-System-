<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_journeys', function (Blueprint $table) {
            if (!Schema::hasColumn('product_journeys', 'quantity_moved')) {
                $table->decimal('quantity_moved', 15, 2)->nullable()->after('selling_price');
            }
            if (!Schema::hasColumn('product_journeys', 'quantity_unit')) {
            $table->string('quantity_unit')->nullable()->after('quantity_moved');
            }
            if (!Schema::hasColumn('product_journeys', 'loss_quantity')) {
                $table->decimal('loss_quantity', 15, 2)->nullable()->after('quantity_unit');
            }
            if (!Schema::hasColumn('product_journeys', 'from_location')) {
                $table->string('from_location')->nullable()->after('buyer_id');
            }
            if (!Schema::hasColumn('product_journeys', 'to_location')) {
                $table->string('to_location')->nullable()->after('from_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_journeys', function (Blueprint $table) {
            $table->dropColumn([
                'quantity_moved',
                'quantity_unit',
                'loss_quantity',
                'from_location',
                'to_location',
            ]);
        });
    }
};
