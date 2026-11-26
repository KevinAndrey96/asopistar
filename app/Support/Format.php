<?php

namespace App\Support;

use DateTimeInterface;

/**
 * Colombian number and date formatting for views and PDF reports.
 */
final class Format
{
    private const array MONTHS = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

    public function number(float|int|string|null $value, int $decimals = 0): string
    {
        return number_format((float) $value, $decimals, ',', '.');
    }

    public function kg(float|int|string|null $value): string
    {
        $value = (float) $value;

        return $this->number($value, abs($value) < 100 && fmod($value, 1.0) !== 0.0 ? 2 : 0).' kg';
    }

    public function grams(float|int|string|null $value): string
    {
        return $this->number($value, (float) $value < 10 ? 1 : 0).' g';
    }

    public function percent(?float $ratio): string
    {
        return $ratio === null ? 'N/D' : $this->number($ratio * 100, 1).' %';
    }

    public function ratio(?float $value): string
    {
        return $value === null ? 'N/D' : $this->number($value, 2);
    }

    public function cop(int $value): string
    {
        return '$ '.$this->number($value);
    }

    public function date(?DateTimeInterface $date): string
    {
        if ($date === null) {
            return '';
        }

        return $date->format('j').' '.self::MONTHS[(int) $date->format('n') - 1].' '.$date->format('Y');
    }
}
