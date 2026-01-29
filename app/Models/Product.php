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
        'product_image',
        'selling_price',
        'reorder_level',
        'is_active',
    ];

    public function stockBalance()
    {
        return $this->hasOne(StockBalance::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
