<?php

namespace App\Http\Requests\Admin;

use App\Domain\Tax\Nit;
use App\Models\Provider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

final class ProviderRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $provider = $this->route('provider');

        return [
            'name' => ['required', 'string', 'max:120'],
            'nit' => [
                'required', 'string', 'max:20',
                function (string $attribute, mixed $value, Closure $fail): void {
                    try {
                        Nit::parse((string) $value);
                    } catch (InvalidArgumentException) {
                        $fail('El NIT no es válido: revisa los dígitos y el dígito de verificación.');
                    }
                },
            ],
            'city' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'regex:/^\+?[\d ]{7,20}$/'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'normalized_nit' => [Rule::unique('providers', 'nit')->ignore($provider instanceof Provider ? $provider->id : null)],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['normalized_nit.unique' => 'Ya existe un proveedor con ese NIT.'];
    }

    /** @return array<string, mixed> */
    public function provider(): array
    {
        return [...$this->safe()->except(['nit', 'normalized_nit']), 'nit' => $this->input('normalized_nit')];
    }

    protected function prepareForValidation(): void
    {
        try {
            $this->merge(['normalized_nit' => (string) Nit::parse($this->string('nit')->toString())]);
        } catch (InvalidArgumentException) {
            $this->merge(['normalized_nit' => null]);
        }
    }
}
