<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'manufacturer_id' => 'nullable|integer|exists:manufacturers,id',
            'service_id' => 'nullable|integer|exists:services,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|gte:price_min',
            'sort' => 'nullable|in:price_asc,price_desc,name_asc,name_desc',
        ];
    }
}
