<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $guarded = []; // ফিউচার প্রুফ: নতুন কলাম যোগ করলে এখানে হাত দিতে হবে না

    // রিলেশন: প্রোডাক্ট কোন ক্যাটাগরির
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // রিলেশন: প্রোডাক্টের পরিমাপের ইউনিট কি
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
