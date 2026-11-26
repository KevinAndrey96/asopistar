<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesDecimals;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StartCycleRequest extends FormRequest
{
    use NormalizesDecimals;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'species_id' => ['required', 'integer', 'exists:species,id'],
            'provider_id' => ['nullable', 'integer', 'exists:providers,id'],
            'stocked_fish' => ['required', 'integer', 'min:1', 'max:5000000'],
            'initial_weight_grams' => ['required', 'numeric', 'min:0.01', 'max:2000'],
            'stocked_on' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
        ];
    }

    /**
     * @return array{species_id: int, provider_id: int|null, stocked_fish: int, initial_weight_grams: string, stocked_on: string}
     */
    public function cycle(): array
    {
        return [
            'species_id' => $this->integer('species_id'),
            'provider_id' => $this->filled('provider_id') ? $this->integer('provider_id') : null,
            'stocked_fish' => $this->integer('stocked_fish'),
            'initial_weight_grams' => $this->string('initial_weight_grams')->toString(),
            'stocked_on' => $this->string('stocked_on')->toString(),
        ];
    }

    protected function decimalFields(): array
    {
        return ['initial_weight_grams'];
    }
}
