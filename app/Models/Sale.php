<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'sale_date',
        'total_amount',
        'cashier_id',
        'amount_change',
        'amount_paid',
        'change',
    ];

    protected $casts = [
        'sale_date' => 'datetime', // <- this ensures you get a Carbon instance
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
