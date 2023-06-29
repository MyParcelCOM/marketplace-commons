<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use MyParcelCom\Integration\Price;
use PHPUnit\Framework\TestCase;
use function random_int;

class PriceTest extends TestCase
{
    public function test_it_should_convert_price_into_array(): void
    {
        $amount = random_int(100, 900);
        $currencyCode = Factory::create()->currencyCode();

        $price = new Price($amount, $currencyCode);

        self::assertEquals(['amount' => $amount, 'currency' => $currencyCode], $price->toArray());
    }
}
