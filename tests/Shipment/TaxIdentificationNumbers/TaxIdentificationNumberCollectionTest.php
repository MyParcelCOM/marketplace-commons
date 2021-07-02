<?php

declare(strict_types=1);

namespace Tests\Shipment\TaxIdentificationNumbers;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Shipment\Items\Item;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\Enums\TaxNumberTypeEnum;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumber;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumberCollection;
use PHPUnit\Framework\TestCase;
use function random_int;

class TaxIdentificationNumberCollectionTest extends TestCase
{
    public function test_it_should_convert_items_collection_to_array(): void
    {
        $faker = Factory::create();
        $firstNumber = Mockery::mock(TaxIdentificationNumber::class, [
            'toArray' => [
                'country_code' => $faker->countryCode,
                'type'         => TaxNumberTypeEnum::EORI(),
                'description'  => $faker->text(25),
                'number'       => $faker->text(9),
            ],
        ]);
        $secondNumber = Mockery::mock(TaxIdentificationNumber::class, [
            'toArray' => [
                'country_code' => $faker->countryCode,
                'type'         => TaxNumberTypeEnum::IOSS(),
                'description'  => $faker->text(25),
                'number'       => $faker->text(9),
            ],
        ]);

        $collection = new TaxIdentificationNumberCollection(
            $firstNumber,
            $secondNumber
        );

        self::assertEquals([$firstNumber->toArray(), $secondNumber->toArray()], $collection->toArray());
    }
}
