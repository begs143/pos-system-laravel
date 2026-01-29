<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    protected $fillable = [
        'product_id',
        'quantity_on_hand',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
