<?php

namespace App\Http\Requests\Admin;

use App\Models\Species;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SpeciesRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $species = $this->route('species');

        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('species')->ignore($species instanceof Species ? $species->id : null)],
            'scientific_name' => ['nullable', 'string', 'max:120'],
            'fry_until_week' => ['required', 'integer', 'min:1', 'max:104'],
            'grow_out_until_week' => ['required', 'integer', 'gt:fry_until_week', 'max:156'],
            'harvest_week' => ['required', 'integer', 'gt:grow_out_until_week', 'max:208'],
            'market_weight_grams' => ['required', 'integer', 'min:1', 'max:50000'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'grow_out_until_week.gt' => 'El levante debe terminar después de la cría.',
            'harvest_week.gt' => 'La cosecha debe caer en la etapa de ceba, después del levante.',
        ];
    }
}
