<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\NormalizesDecimals;
use App\Models\FeedBrand;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class FeedBrandRequest extends FormRequest
{
    use NormalizesDecimals;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $brand = $this->route('feed_brand');

        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('feed_brands')->ignore($brand instanceof FeedBrand ? $brand->id : null)],
            'protein_percent' => ['required', 'numeric', 'min:10', 'max:70'],
        ];
    }

    protected function decimalFields(): array
    {
        return ['protein_percent'];
    }
}
