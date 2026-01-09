<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

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
        'production_cost_per_unit',
        'certification_type',
        'storage_condition',
        'water_footprint',
        'target_market',
        'safety_score',
        'inspector_id',
        'last_pesticide_date',
        'latitude',
        'longitude',
        'quality_grade',
        'current_location',
        'farmer_price',
        'processing_cost',
        'target_retail_price',
    ];

    // ডেট কাস্টিং (এটি করলে ব্লেড ফাইলে সরাসরি format() ব্যবহার করতে পারবেন)
    protected $casts = [
        'sowing_date' => 'date',
        'harvest_date' => 'date',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'last_pesticide_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Stakeholder::class, 'initial_farmer_id');
    }

    /**
     * স্মার্ট এট্রিবিউট: ম্যচুরিটি ডেইজ ক্যালকুলেশন
     * এখন $batch->cultivation_days দিলেই ভ্যালু চলে আসবে
     */
    public function getCultivationDaysAttribute()
    {
        if ($this->sowing_date && $this->harvest_date) {
            return $this->sowing_date->diffInDays($this->harvest_date);
        }
        return null;
    }
}
