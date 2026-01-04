<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'farmers';


     // protected $guarded = []; ব্যবহার করা হয়েছে যাতে সব কলামে ডাটা ইনসার্ট করা যায়।

    protected $guarded = [];

    
    public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class, 'stakeholder_id');
    }
}
