<?php

namespace App\Enums;

enum Role: string
{
    /** Runs the association: farmers, catalogs, public content and global reports. */
    case Admin = 'administrador';

    /** Keeps the logbook of their own ponds. */
    case Farmer = 'piscicultor';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Farmer => 'Piscicultor',
        };
    }
}
