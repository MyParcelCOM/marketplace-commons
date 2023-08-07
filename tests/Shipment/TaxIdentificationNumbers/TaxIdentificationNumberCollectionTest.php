<?php

declare(strict_types=1);

namespace Tests\Shipment\TaxIdentificationNumbers;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumber;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumberCollection;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxNumberType;
use PHPUnit\Framework\TestCase;

class TaxIdentificationNumberCollectionTest extends TestCase
{
    public function test_it_should_convert_items_collection_to_array(): void
    {
        $faker = Factory::create();
        $firstSet = [
            'country_code' => $faker->countryCode(),
            'type'         => TaxNumberType::EORI,
            'description'  => $faker->text(25),
            'number'       => $faker->text(9),
        ];

        $secondSet = [
            'country_code' => $faker->countryCode(),
            'type'         => TaxNumberType::IOSS,
            'description'  => $faker->text(25),
            'number'       => $faker->text(9),
        ];

        $firstNumber = Mockery::mock(TaxIdentificationNumber::class, [
            'toArray' => $firstSet,
        ]);
        $secondNumber = Mockery::mock(TaxIdentificationNumber::class, [
            'toArray' => $secondSet,
        ]);

        $collection = new TaxIdentificationNumberCollection(
            $firstNumber,
            $secondNumber
        );

        self::assertEquals([$firstSet, $secondSet], $collection->toArray());
    }
}
