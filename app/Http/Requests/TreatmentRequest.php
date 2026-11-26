<?php

namespace App\Http\Requests;

final class TreatmentRequest extends LogbookRequest
{
    protected $errorBag = 'treatment';

    protected function dateField(): string
    {
        return 'applied_on';
    }

    protected function entryRules(): array
    {
        return [
            'agent' => ['required', 'string', 'max:120'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
