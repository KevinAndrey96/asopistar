<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Base for public content with an optional photo.
 */
abstract class ContentRequest extends FormRequest
{
    /** @return array<string, ValidationRule|array<mixed>|string> */
    abstract protected function contentRules(): array;

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            ...$this->contentRules(),
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:max_width=6000,max_height=6000'],
            'remove_image' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, string> */
    public function attributes(): array
    {
        return ['image' => 'foto'];
    }

    public function photo(): ?UploadedFile
    {
        $file = $this->file('image');

        return $file instanceof UploadedFile ? $file : null;
    }

    /** @return array<string, mixed> */
    public function content(): array
    {
        return $this->safe()->except(['image', 'remove_image']);
    }
}
