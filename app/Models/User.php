<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property Role $role
 * @property string $document
 * @property string|null $member_code
 * @property Gender|null $gender
 * @property string|null $phone
 * @property string|null $estate
 * @property string|null $vereda
 * @property bool $active
 */
#[Fillable(['name', 'last_name', 'email', 'password', 'role', 'document', 'member_code', 'gender', 'phone', 'estate', 'vereda', 'active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => Role::class,
            'gender' => Gender::class,
            'active' => 'boolean',
        ];
    }

    /** @return HasMany<Pond, $this> */
    public function ponds(): HasMany
    {
        return $this->hasMany(Pond::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function fullName(): string
    {
        return $this->name.' '.$this->last_name;
    }

    /**
     * @param  Builder<self>  $query
     */
    public function scopeFarmers(Builder $query): void
    {
        $query->where('role', Role::Farmer->value);
    }
}
