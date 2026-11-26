<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesDecimals;
use App\Models\Pond;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PondRequest extends FormRequest
{
    use NormalizesDecimals;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $pond = $this->route('pond');

        return [
            'code' => [
                'required', 'string', 'max:30', 'regex:/^[\pL\pN ._-]+$/u',
                Rule::unique('ponds')->where('user_id', $this->user()?->id)->ignore($pond instanceof Pond ? $pond->id : null),
            ],
            'area_m2' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'water_flow_lps' => ['required', 'numeric', 'min:0', 'max:10000'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['code.unique' => 'Ya tienes un estanque con ese código.'];
    }

    protected function decimalFields(): array
    {
        return ['area_m2', 'water_flow_lps'];
    }
}
