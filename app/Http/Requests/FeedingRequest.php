<?php

namespace App\Http\Requests;

final class FeedingRequest extends LogbookRequest
{
    protected $errorBag = 'feeding';

    protected function dateField(): string
    {
        return 'fed_on';
    }

    protected function entryRules(): array
    {
        return [
            'feed_brand_id' => ['required', 'integer', 'exists:feed_brands,id'],
            'kilograms' => ['required', 'numeric', 'min:0.01', 'max:100000'],
        ];
    }

    protected function decimalFields(): array
    {
        return ['kilograms'];
    }
}
