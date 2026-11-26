<?php

namespace App\Models;

use Database\Factories\PondFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property string $area_m2
 * @property string $water_flow_lps
 * @property string|null $equipment
 * @property bool $active
 * @property-read User $user
 * @property-read Cycle|null $openCycle
 */
#[Fillable(['code', 'area_m2', 'water_flow_lps', 'equipment', 'active'])]
class Pond extends Model
{
    /** @use HasFactory<PondFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'area_m2' => 'decimal:2',
            'water_flow_lps' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Cycle, $this> */
    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class);
    }

    /** @return HasOne<Cycle, $this> */
    public function openCycle(): HasOne
    {
        return $this->hasOne(Cycle::class)->ofMany(['id' => 'max'], fn ($query) => $query->whereNull('closed_on'));
    }
}
