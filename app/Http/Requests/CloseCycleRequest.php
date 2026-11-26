<?php

namespace App\Http\Requests;

final class CloseCycleRequest extends LogbookRequest
{
    protected $errorBag = 'close';

    protected function dateField(): string
    {
        return 'closed_on';
    }

    protected function entryRules(): array
    {
        return [];
    }
}
