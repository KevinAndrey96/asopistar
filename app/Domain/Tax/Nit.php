<?php

namespace App\Domain\Tax;

use InvalidArgumentException;
use Stringable;

/**
 * Colombian tax identification number with its DIAN check digit.
 */
final readonly class Nit implements Stringable
{
    private const array WEIGHTS = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];

    private function __construct(
        public string $number,
        public int $checkDigit,
    ) {}

    /**
     * Accepts "900123456-7", "900.123.456-7" or the bare number, in which
     * case the check digit is computed.
     */
    public static function parse(string $value): self
    {
        $clean = preg_replace('/[\s.]/', '', $value) ?? '';
        if (preg_match('/^(\d{6,15})(?:-(\d))?$/', $clean, $parts) !== 1) {
            throw new InvalidArgumentException('nit must have 6 to 15 digits and an optional check digit');
        }
        $number = ltrim($parts[1], '0');
        if ($number === '') {
            throw new InvalidArgumentException('nit cannot be zero');
        }
        $expected = self::checkDigitOf($number);
        if (isset($parts[2]) && (int) $parts[2] !== $expected) {
            throw new InvalidArgumentException('nit check digit does not match');
        }

        return new self($number, $expected);
    }

    public static function checkDigitOf(string $number): int
    {
        $sum = 0;
        foreach (array_reverse(str_split($number)) as $i => $digit) {
            $sum += (int) $digit * self::WEIGHTS[$i];
        }
        $remainder = $sum % 11;

        return $remainder > 1 ? 11 - $remainder : $remainder;
    }

    public function formatted(): string
    {
        return number_format((int) $this->number, 0, ',', '.').'-'.$this->checkDigit;
    }

    public function __toString(): string
    {
        return $this->number.'-'.$this->checkDigit;
    }
}
