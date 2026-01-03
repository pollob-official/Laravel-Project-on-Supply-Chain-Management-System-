<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
{
    use HasFactory;
    use SoftDeletes; // আপনার রিকুয়েস্ট অনুযায়ী সফট ডিলিট যোগ করা হয়েছে


    protected $table = 'farmers';

    /**
     * protected $guarded = []; ব্যবহার করা হয়েছে যাতে সব কলামে ডাটা ইনসার্ট করা যায়।
     */
    protected $guarded = [];

    /**
     * রিলেশনশিপ: একজন কৃষক একটি স্টেকহোল্ডার রেকর্ডের অন্তর্ভুক্ত।
     */
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }
}
