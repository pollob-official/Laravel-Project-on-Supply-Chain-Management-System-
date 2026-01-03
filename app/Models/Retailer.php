<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retailer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'retailers';

    protected $guarded = [];

    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }
}
