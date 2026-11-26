<?php

namespace App\Http\Requests;

final class HarvestRequest extends LogbookRequest
{
    protected $errorBag = 'harvest';

    /**
     * @return array{harvested_on: string, fish_count: int, kilograms: string, destination: string, ice_slaughter_kg: string|null, ice_cooling_kg: string|null, ice_transport_kg: string|null}
     */
    public function harvest(): array
    {
        $ice = fn (string $field): ?string => $this->filled($field) ? $this->string($field)->toString() : null;

        return [
            'harvested_on' => $this->string('harvested_on')->toString(),
            'fish_count' => $this->integer('fish_count'),
            'kilograms' => $this->string('kilograms')->toString(),
            'destination' => $this->string('destination')->trim()->toString(),
            'ice_slaughter_kg' => $ice('ice_slaughter_kg'),
            'ice_cooling_kg' => $ice('ice_cooling_kg'),
            'ice_transport_kg' => $ice('ice_transport_kg'),
        ];
    }

    protected function dateField(): string
    {
        return 'harvested_on';
    }

    protected function entryRules(): array
    {
        return [
            'fish_count' => ['required', 'integer', 'min:1'],
            'kilograms' => ['required', 'numeric', 'min:0.1', 'max:1000000'],
            'destination' => ['required', 'string', 'max:120'],
            'ice_slaughter_kg' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'ice_cooling_kg' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'ice_transport_kg' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'close_cycle' => ['sometimes', 'boolean'],
        ];
    }

    protected function decimalFields(): array
    {
        return ['kilograms', 'ice_slaughter_kg', 'ice_cooling_kg', 'ice_transport_kg'];
    }
}
