<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    // আপনি $guarded = [] ব্যবহার করেছেন যা ভালো, তবে সিকিউরিটির জন্য $fillable ব্যবহার করা স্ট্যান্ডার্ড।
    // যেহেতু আপনি ফিউচার প্রুফ রাখতে চান, তাই আমি $guarded ই রাখছি।
    protected $guarded = [];

    /**
     * রিলেশন: প্রোডাক্ট কোন ক্যাটাগরির
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * রিলেশন: প্রোডাক্টের পরিমাপের ইউনিট কি
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * রিলেশন: এই প্রোডাক্টের যতগুলো পারচেজ (কেনাকাটা) হয়েছে
     * এটি সাপ্লাই চেইন ট্র্যাকিংয়ের জন্য জরুরি
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }


    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->alert_quantity;
    }

}
