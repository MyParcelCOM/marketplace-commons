<?php

declare(strict_types=1);

namespace Tests\Shipment\TaxIdentificationNumbers;

use Faker\Factory;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxNumberType;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumber;
use PHPUnit\Framework\TestCase;

class TaxIdentificationNumberTest extends TestCase
{
    public function test_it_should_return_full_item_with_all_inputs(): void
    {
        $faker = Factory::create();
        $countryCode = $faker->countryCode();
        $type = new TaxNumberType('eori');
        $description = $faker->text(25);
        $number = $faker->text(9);

        $item = new TaxIdentificationNumber(
            countryCode: $countryCode,
            type: $type,
            number: $number,
            description: $description,
        );

        self::assertEquals([
            'description' => $description,
            'country_code' => $countryCode,
            'number'      => $number,
            'type'        => 'eori',
        ], $item->toArray());
    }

    public function test_it_should_return_full_item_with_empty_description(): void
    {
        $faker = Factory::create();
        $countryCode = $faker->countryCode();
        $type = new TaxNumberType('eori');
        $description = null;
        $number = $faker->text(9);

        $item = new TaxIdentificationNumber(
            countryCode: $countryCode,
            type: $type,
            number: $number,
            description: $description,
        );

        self::assertEquals([
            'country_code' => $countryCode,
            'number'      => $number,
            'type'        => 'eori',
        ], $item->toArray());
    }
}
