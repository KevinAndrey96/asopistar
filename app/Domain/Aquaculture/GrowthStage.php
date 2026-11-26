<?php

namespace App\Domain\Aquaculture;

enum GrowthStage: string
{
    case Fry = 'cria';
    case GrowOut = 'levante';
    case Fattening = 'ceba';

    public function label(): string
    {
        return match ($this) {
            self::Fry => 'Cría',
            self::GrowOut => 'Levante',
            self::Fattening => 'Ceba',
        };
    }
}
