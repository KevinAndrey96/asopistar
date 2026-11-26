<?php

namespace App\Http\Requests\Admin;

final class PostRequest extends ContentRequest
{
    protected function contentRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'excerpt' => ['required', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:20000'],
            'published_on' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
