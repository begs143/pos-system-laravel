<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'image',
        'category_id',
        'unit_id',
        'cost_price',
        'selling_price',
        'reorder_level',
        'is_active',
    ];
}
