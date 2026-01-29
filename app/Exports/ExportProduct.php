<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportProduct implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::with(['category', 'unit', 'stockBalance'])
            ->get()
            ->map(function ($product) {
                return [
                    $product->product_code,
                    $product->name,
                    $product->category->name ?? '-',
                    $product->cost_price,
                    $product->selling_price,
                    $product->stockBalance->quantity_on_hand ?? 0,
                    $product->unit->abbreviation ?? 'pcs',
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->created_at->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Product Code',
            'Product Name',
            'Category',
            'Cost Price',
            'Selling Price',
            'Quantity',
            'Unit',
            'Status',
            'Created Date',
        ];
    }
}
