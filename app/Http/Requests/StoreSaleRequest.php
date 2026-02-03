<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_no' => ['prohibited'],
            'sale_date' => ['required', 'date'],
            'cashier_id' => ['required', 'exists:users,id'],
            'total_amount' => ['prohibited'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'cash_amount' => ['required', 'numeric', 'min:0'],
        ];
    }
}
