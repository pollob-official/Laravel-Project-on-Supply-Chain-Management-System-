<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('site_name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('currency_symbol')->default('৳');
        $table->string('logo')->nullable();
        $table->string('favicon')->nullable();
        $table->text('address')->nullable();
        $table->string('footer_copy')->nullable();
        $table->timestamps();
    });

    // ডিফল্ট একটি রো ইনসার্ট করা থাকলে এরর আসবে না
    DB::table('settings')->insert([
        'site_name' => 'SmartAgri ERP',
        'currency_symbol' => '৳',
        'created_at' => now(),
    ]);
}
};
