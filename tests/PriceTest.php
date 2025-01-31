<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use MyParcelCom\Integration\Price;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class PriceTest extends TestCase
{
    /**
     * @throws \Random\RandomException
     */
    public function test_it_should_convert_price_into_array(): void
    {
        $amount = random_int(100, 900);
        $currencyCode = Factory::create()->currencyCode();

        $price = new Price($amount, $currencyCode);

        assertEquals(['amount' => $amount, 'currency' => $currencyCode], $price->toArray());
    }
}
