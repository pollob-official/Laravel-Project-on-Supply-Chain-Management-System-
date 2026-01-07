<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model {
    use SoftDeletes;

    protected $guarded = []; 


    public function product() {
        return $this->belongsTo(Product::class, 'product_id')->withDefault(['name' => 'N/A']);
    }

    public function farmer() {
        return $this->belongsTo(Stakeholder::class, 'initial_farmer_id')->withDefault(['name' => 'N/A']);
    }
}
