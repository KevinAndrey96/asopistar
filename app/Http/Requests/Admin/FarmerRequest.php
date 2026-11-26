<?php

namespace App\Http\Requests\Admin;

use App\Enums\Gender;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class FarmerRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $farmer = $this->route('farmer');
        $ignore = $farmer instanceof User ? $farmer->id : null;

        return [
            'name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users')->ignore($ignore)],
            'document' => ['required', 'regex:/^\d{5,15}$/', Rule::unique('users')->ignore($ignore)],
            'member_code' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($ignore)],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'phone' => ['nullable', 'regex:/^\+?[\d ]{7,20}$/'],
            'estate' => ['nullable', 'string', 'max:120'],
            'vereda' => ['nullable', 'string', 'max:120'],
            'active' => ['sometimes', 'boolean'],
            'password' => [$ignore === null ? 'required' : 'nullable', 'confirmed', Password::defaults()],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['document' => 'cédula', 'member_code' => 'código de asociado', 'vereda' => 'vereda'];
    }

    /** @return array<string, mixed> */
    public function farmer(): array
    {
        $data = $this->safe()->except(['password', 'password_confirmation']);
        $data['active'] = $this->boolean('active');
        if ($this->filled('password')) {
            $data['password'] = $this->string('password')->toString();
        }

        return $data;
    }
}
