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
        Schema::create('product_journeys', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_no'); // ওই বস্তার একটা ইউনিক নাম্বার (যেমন: B-001)
            $table->integer('product_id'); // কোন পণ্য (যেমন: মিনিকেট চাল)
            $table->integer('seller_id'); // কে বিক্রি করল (যেমন: কৃষক রহিম)
            $table->integer('buyer_id'); // কে কিনল (যেমন: মিলার শফিক)

            $table->decimal('buying_price', 15, 2); // কত দিয়ে কেনা হলো
            $table->decimal('extra_cost', 15, 2)->default(0); // এই ধাপে খরচ কত (লেবার/গাড়ি ভাড়া)
            $table->decimal('profit_margin', 15, 2); // সে কত লাভ করতে চায়
            $table->decimal('selling_price', 15, 2); // সব যোগ করে এই ধাপের শেষ দাম

            $table->string('current_stage'); // এখন কোন ধাপে আছে (Farmer/Miller/Wholesaler)
            $table->timestamps();
            $table->softDeletes(); // আপনার রিকোয়েস্ট অনুযায়ী সফট ডিলিট অপশন
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_journeys');
    }
};
