<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MillersSupplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'millers_suppliers'; // মাইগ্রেশন অনুযায়ী টেবিল নাম

    protected $guarded = []; // সব কলামে ডাটা ইনসার্ট করার অনুমতি

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }
}
