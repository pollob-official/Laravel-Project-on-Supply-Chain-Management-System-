<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductJourney extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getEventTypeAttribute()
    {
        return $this->current_stage;
    }

    public function getQuantityUnitAttribute()
    {
        return $this->quantity_unit;
    }

    // কোন ব্যাচের আন্ডারে এই হ্যান্ডওভার হচ্ছে
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    // কোন প্রোডাক্টের জার্নি সেটা দেখার জন্য
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // কে বিক্রি করল (বিক্রেতা)
    public function seller()
    {
        return $this->belongsTo(Stakeholder::class, 'seller_id');
    }

    // কে কিনল (ক্রেতা)
    public function buyer()
    {
        return $this->belongsTo(Stakeholder::class, 'buyer_id');
    }
}
