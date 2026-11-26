<?php

namespace Tests\Unit;

use App\Support\Format;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class FormatTest extends TestCase
{
    public function test_uses_colombian_separators(): void
    {
        $fmt = new Format;

        $this->assertSame('1.234.567', $fmt->number(1234567));
        $this->assertSame('12,50', $fmt->number(12.5, 2));
        $this->assertSame('$ 32.000', $fmt->cop(32000));
    }

    public function test_formats_weights_and_ratios(): void
    {
        $fmt = new Format;

        $this->assertSame('1.250 kg', $fmt->kg(1250));
        $this->assertSame('12,50 kg', $fmt->kg(12.5));
        $this->assertSame('2,5 g', $fmt->grams(2.5));
        $this->assertSame('450 g', $fmt->grams(450));
        $this->assertSame('95,0 %', $fmt->percent(0.95));
        $this->assertSame('N/D', $fmt->ratio(null));
    }

    public function test_formats_dates_in_spanish(): void
    {
        $this->assertSame('5 mar 2026', (new Format)->date(new DateTimeImmutable('2026-03-05')));
        $this->assertSame('', (new Format)->date(null));
    }
}
