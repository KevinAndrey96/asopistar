<?php

namespace App\Http\Requests;

final class SamplingRequest extends LogbookRequest
{
    protected $errorBag = 'sampling';

    protected function dateField(): string
    {
        return 'sampled_on';
    }

    protected function entryRules(): array
    {
        return [
            'sample_size' => ['required', 'integer', 'min:1', 'max:10000'],
            'sample_weight_grams' => ['required', 'numeric', 'min:0.1', 'max:10000000'],
            'mortality' => ['nullable', 'integer', 'min:0', 'max:'.$this->cycle()->metrics()->population()],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            ...parent::messages(),
            'mortality.max' => 'La mortalidad supera los :max peces que quedan según la bitácora.',
        ];
    }

    protected function decimalFields(): array
    {
        return ['sample_weight_grams'];
    }
}
