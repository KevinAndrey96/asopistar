<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Keeps a unique, URL friendly slug derived from a title-like attribute.
 */
trait HasSlug
{
    abstract protected function slugSource(): string;

    protected static function bootHasSlug(): void
    {
        static::saving(function (self $model): void {
            if ($model->isDirty($model->slugSource()) || $model->getAttribute('slug') === null) {
                $model->setAttribute('slug', $model->uniqueSlug((string) $model->getAttribute($model->slugSource())));
            }
        });
    }

    private function uniqueSlug(string $source): string
    {
        $base = Str::limit(Str::slug($source), 150, '') ?: 'item';
        $slug = $base;
        for ($n = 2; $this->slugTaken($slug); $n++) {
            $slug = $base.'-'.$n;
        }

        return $slug;
    }

    private function slugTaken(string $slug): bool
    {
        return static::query()
            ->where('slug', $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists();
    }
}
