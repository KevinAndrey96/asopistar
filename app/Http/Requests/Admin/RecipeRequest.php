<?php

namespace App\Http\Requests\Admin;

final class RecipeRequest extends ContentRequest
{
    protected function contentRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'summary' => ['required', 'string', 'max:300'],
            'ingredients' => ['required', 'string', 'max:5000'],
            'steps' => ['required', 'string', 'max:10000'],
        ];
    }
}
