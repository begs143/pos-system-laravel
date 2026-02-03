<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleItemsRequest extends FormRequest
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
            'items' => ['required', 'array', 'min:1'], // must be a non-empty array
            'items.*.id' => ['required', 'exists:products,id'], // each item must have a valid product ID
            'items.*.qty' => ['required', 'integer', 'min:1'], // quantity must be integer >= 1
            'items.*.price' => ['required', 'numeric', 'min:0'], // price must be numeric >= 0
        ];

    }
}
