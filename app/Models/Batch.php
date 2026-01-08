<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    // আপনার ২১টি কলাম + ৫টি স্মার্ট আইডিয়া কলাম সব এখানে আছে
    protected $fillable = [
        'batch_no',
        'product_id',
        'initial_farmer_id',
        'seed_brand',
        'seed_variety',
        'sowing_date',
        'pesticide_history',
        'fertilizer_history',
        'total_quantity',
        'qr_code',
        'qc_status',
        'qc_officer_name',
        'qc_remarks',
        'manufacturing_date',
        'harvest_date',
        'moisture_level',
        'expiry_date',

        // --- স্মার্ট এগ্রিকালচার ফিচার কলাম ---
        'last_pesticide_date', // কীটনাশক সেফটি চেক করার জন্য
        'latitude',            // লোকেশন ট্র্যাকিং
        'longitude',           // লোকেশন ট্র্যাকিং
        'quality_grade',       // এ-গ্রেড, বি-গ্রেড
        'current_location',    // সাপ্লাই চেইন স্টেজ (Field, Processing, Market)
    ];

    /**
     * প্রোডাক্টের সাথে রিলেশন
     * প্রতিটি ব্যাচ একটি নির্দিষ্ট প্রোডাক্টের (যেমন: মিনিকেট চাল) আন্ডারে থাকে।
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * কৃষকের সাথে রিলেশন
     * স্টেকহোল্ডার টেবিল থেকে শুধুমাত্র 'farmer' রোলের ডাটা আসবে।
     */
    public function farmer()
    {
        return $this->belongsTo(Stakeholder::class, 'initial_farmer_id');
    }

    /**
     * স্মার্ট এট্রিবিউট: ম্যচুরিটি ডেইজ ক্যালকুলেশন
     * এটি ব্যবহার করে আপনি ব্লেড ফাইলে সরাসরি কতদিন চাষ হয়েছে তা দেখাতে পারবেন।
     */
    public function getCultivationDaysAttribute()
    {
        if ($this->sowing_date && $this->harvest_date) {
            $sowing = \Carbon\Carbon::parse($this->sowing_date);
            $harvest = \Carbon\Carbon::parse($this->harvest_date);
            return $sowing->diffInDays($harvest);
        }
        return null;
    }
}
