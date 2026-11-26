<?php

namespace App\Http\Requests\Concerns;

/**
 * Accepts the Colombian decimal comma ("12,5") in numeric fields.
 */
trait NormalizesDecimals
{
    /** @return list<string> */
    abstract protected function decimalFields(): array;

    protected function prepareForValidation(): void
    {
        $normalized = [];
        foreach ($this->decimalFields() as $field) {
            $value = $this->input($field);
            if (is_string($value)) {
                $normalized[$field] = str_replace([' ', ','], ['', '.'], trim($value));
            }
        }
        $this->merge($normalized);
    }
}
