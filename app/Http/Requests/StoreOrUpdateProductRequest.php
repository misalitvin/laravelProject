<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOrUpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'manufacturer_id' => ['required', 'exists:manufacturers,id'],
            'release_date' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'services' => ['array'],
        ];

        $services = $this->input('services', []);

        foreach ($services as $serviceId => $serviceData) {
            $rules["services.$serviceId.selected"] = ['nullable'];

            if (! empty($serviceData['selected'])) {
                $rules["services.$serviceId.days_to_complete"] = ['required', 'integer', 'min:1'];
                $rules["services.$serviceId.cost"] = ['required', 'numeric', 'min:0'];
            } else {
                $rules["services.$serviceId.days_to_complete"] = ['nullable', 'integer', 'min:1'];
                $rules["services.$serviceId.cost"] = ['nullable', 'numeric', 'min:0'];
            }
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name cannot exceed 255 characters.',

            'manufacturer_id.required' => 'Manufacturer is required.',
            'manufacturer_id.exists' => 'Selected manufacturer does not exist.',

            'release_date.required' => 'Release date is required.',
            'release_date.date' => 'Release date must be a valid date.',

            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be at least 0.',

            'description.required' => 'Description is required.',
            'description.string' => 'Description must be a string.',

            'services.*.days_to_complete.required' => 'Days to complete is required when the service is selected.',
            'services.*.days_to_complete.integer' => 'Days to complete must be a whole number.',
            'services.*.days_to_complete.min' => 'Days to complete must be at least 1.',

            'services.*.cost.required' => 'Cost is required when the service is selected.',
            'services.*.cost.numeric' => 'Cost must be a valid number.',
            'services.*.cost.min' => 'Cost must be at least 0.',
        ];
    }
}
