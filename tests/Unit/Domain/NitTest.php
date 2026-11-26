<?php

namespace Tests\Unit\Domain;

use App\Domain\Tax\Nit;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NitTest extends TestCase
{
    /** @return iterable<string, array{string, string, string}> */
    public static function validNits(): iterable
    {
        yield 'DIAN with check digit' => ['800197268-4', '800197268-4', '800.197.268-4'];
        yield 'dots and spaces' => [' 800.197.268 - 4 ', '800197268-4', '800.197.268-4'];
        yield 'without check digit' => ['900456781', '900456781-6', '900.456.781-6'];
        yield 'leading zeros' => ['0860002964', '860002964-4', '860.002.964-4'];
    }

    #[DataProvider('validNits')]
    public function test_parses_and_normalizes(string $input, string $normalized, string $formatted): void
    {
        $nit = Nit::parse(str_replace(' - ', '-', $input));

        $this->assertSame($normalized, (string) $nit);
        $this->assertSame($formatted, $nit->formatted());
    }

    /** @return iterable<string, array{string}> */
    public static function invalidNits(): iterable
    {
        yield 'wrong check digit' => ['800197268-5'];
        yield 'letters' => ['80019A268'];
        yield 'too short' => ['12345'];
        yield 'zero' => ['000000000'];
        yield 'empty' => [''];
    }

    #[DataProvider('invalidNits')]
    public function test_rejects_invalid_numbers(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);

        Nit::parse($input);
    }

    public function test_check_digit_handles_remainders_zero_and_one(): void
    {
        $this->assertSame(0, Nit::checkDigitOf('15'));
        $this->assertSame(1, Nit::checkDigitOf('4'));
    }
}
