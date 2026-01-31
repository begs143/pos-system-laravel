<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'type',
        'user_id',
        'quantity',
        'remarks',
    ];

    // StockMovement belongs to a Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    // StockMovement belongs to a Supplier (nullable)
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Supplier::class);
    }

    // StockMovement belongs to a User
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
