<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // এই কলামগুলোতে ডাটা ইনসার্ট করার পারমিশন দেওয়া হলো
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active'
    ];
}
