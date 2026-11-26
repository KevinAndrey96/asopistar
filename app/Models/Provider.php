<?php

namespace App\Models;

use App\Domain\Tax\Nit;
use Database\Factories\ProviderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Supplier of fingerlings, stored with its NIT normalized as "number-digit".
 *
 * @property int $id
 * @property string $name
 * @property string $nit
 * @property string $city
 * @property string|null $phone
 * @property string|null $contact_name
 */
#[Fillable(['name', 'nit', 'city', 'phone', 'contact_name'])]
class Provider extends Model
{
    /** @use HasFactory<ProviderFactory> */
    use HasFactory;

    /** @return HasMany<Cycle, $this> */
    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class);
    }

    public function formattedNit(): string
    {
        return Nit::parse($this->nit)->formatted();
    }
}
