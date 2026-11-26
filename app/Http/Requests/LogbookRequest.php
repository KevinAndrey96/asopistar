<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesDecimals;
use App\Models\Cycle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use LogicException;

/**
 * Base for logbook entries: the date must fall between the stocking and today.
 */
abstract class LogbookRequest extends FormRequest
{
    use NormalizesDecimals;

    abstract protected function dateField(): string;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    abstract protected function entryRules(): array;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            $this->dateField() => [
                'required', 'date_format:Y-m-d', 'before_or_equal:today',
                'after_or_equal:'.$this->cycle()->stocked_on->toDateString(),
            ],
            ...$this->entryRules(),
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            $this->dateField().'.after_or_equal' => 'La fecha no puede ser anterior a la siembra ('.$this->cycle()->stocked_on->format('d/m/Y').').',
        ];
    }

    public function cycle(): Cycle
    {
        $cycle = $this->route('cycle');
        if (! $cycle instanceof Cycle) {
            throw new LogicException('logbook route without a cycle');
        }

        return $cycle;
    }

    protected function decimalFields(): array
    {
        return [];
    }
}
