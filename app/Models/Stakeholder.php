<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stakeholder extends Model
{
    use HasFactory;
    use SoftDeletes;

    // ১. কৃষকের তথ্যের সাথে রিলেশন
    public function farmer()
    {
        return $this->hasOne(Farmer::class, 'stakeholder_id');
    }

    // ২. মিলার/সাপ্লায়ার তথ্যের সাথে রিলেশন
    public function miller()
    {
        return $this->hasOne(MillersSupplier::class, 'stakeholder_id');
    }

    // ৩. পাইকার (Wholesaler) তথ্যের সাথে রিলেশন
    public function wholesaler()
    {
        return $this->hasOne(Wholesaler::class, 'stakeholder_id');
    }

    // ৪. খুচরা বিক্রেতা (Retailer) তথ্যের সাথে রিলেশন
    public function retailer()
    {
        return $this->hasOne(Retailer::class, 'stakeholder_id');
    }
}
