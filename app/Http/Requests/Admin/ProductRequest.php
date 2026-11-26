<?php

namespace App\Http\Requests\Admin;

final class ProductRequest extends ContentRequest
{
    /** @return array<string, mixed> */
    public function content(): array
    {
        return [...parent::content(), 'available' => $this->boolean('available')];
    }

    protected function contentRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'price_cop' => ['required', 'integer', 'min:0', 'max:100000000'],
            'unit' => ['required', 'string', 'max:30'],
            'available' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($price = $this->input('price_cop'))) {
            $this->merge(['price_cop' => preg_replace('/[\s.$]/', '', $price)]);
        }
    }
}
