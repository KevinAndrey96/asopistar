<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Carbon\CarbonImmutable;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * News item published by the association.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $body
 * @property string|null $image_path
 * @property CarbonImmutable $published_on
 */
#[Fillable(['title', 'excerpt', 'body', 'image_path', 'published_on'])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, HasSlug;

    protected function casts(): array
    {
        return ['published_on' => 'immutable_date'];
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereDate('published_on', '<=', CarbonImmutable::today())->latest('published_on')->latest('id');
    }

    protected function slugSource(): string
    {
        return 'title';
    }
}
