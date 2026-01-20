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
        'source_type',
        'source_id',
        'latitude',
        'longitude',
        'manual_address',
        'current_location',
        'seed_brand',
        'seed_variety',
        'sowing_date',
        'harvest_date',
        'last_pesticide_date',
        'pesticide_history',
        'fertilizer_history',
        'processing_method',
        'raw_material_source_batch',
        'total_quantity',
        'unit_name',
        'buying_price_per_unit',
        'processing_cost_per_unit',
        'target_retail_price',
        'currency',
        'qc_status',
        'safety_score',
        'quality_grade',
        'moisture_level',
        'certification_type',
        'manufacturing_date',
        'expiry_date',
        'qr_code',
        'target_market',
        'storage_condition',
        'water_footprint',
        'qc_officer_name',
        'qc_remarks',
    ];

    protected $casts = [
        'sowing_date' => 'date',
        'harvest_date' => 'date',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'last_pesticide_date' => 'date',
        'buying_price_per_unit' => 'decimal:2',
        'processing_cost_per_unit' => 'decimal:2',
        'target_retail_price' => 'decimal:2',
    ];

    // --- Relationships ---

    /**
     * পণ্য বা প্রোডাকশন আইটেম
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * ডায়নামিক সোর্স (Stakeholder)
     * এটি কৃষক, মিলার বা সাপ্লায়ার যে কেউ হতে পারে।
     */
    public function source()
    {
        return $this->belongsTo(Stakeholder::class, 'source_id');
    }

    // --- Smart Attributes / Accessors ---

    /**
     * চাষাবাদের সময়কাল (চাষাবাদ শুরু থেকে ফসল কাটা পর্যন্ত কতদিন)
     */
    public function getCultivationDaysAttribute()
    {
        if ($this->sowing_date && $this->harvest_date) {
            return $this->sowing_date->diffInDays($this->harvest_date);
        }
        return null;
    }

    /**
     * মোট উৎপাদন খরচ (কেনা দাম + প্রসেসিং খরচ)
     */
    public function getTotalProductionCostAttribute()
    {
        return $this->buying_price_per_unit + $this->processing_cost_per_unit;
    }

    /**
     * প্রফিট মার্জিন পার ইউনিট
     */
    public function getProfitMarginAttribute()
    {
        return $this->target_retail_price - $this->total_production_cost;
    }

    /**
     * গ্র্যান্ড টোটাল ভ্যালু (পুরো ব্যাচের মোট বাজার মূল্য)
     */
    public function getTotalBatchValueAttribute()
    {
        return $this->total_quantity * $this->target_retail_price;
    }
}
