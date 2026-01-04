<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductJourney extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // কোন প্রোডাক্টের জার্নি সেটা দেখার জন্য
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // কে বিক্রি করল (বিক্রেতা) - সে একজন স্টেকহোল্ডার
    public function seller()
    {
        return $this->belongsTo(Stakeholder::class, 'seller_id');
    }

    // কে কিনল (ক্রেতা) - সেও একজন স্টেকহোল্ডার
    public function buyer()
    {
        return $this->belongsTo(Stakeholder::class, 'buyer_id');
    }
}
