<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    // mass assignment এর জন্য column গুলো অনুমতি দেওয়া
    protected $fillable = [
        'product_id',
        'qty',
        'price',
        'purchase_date'
    ];

    /**
     * রিলেশন: এই পারচেজটি কোন প্রোডাক্টের
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
