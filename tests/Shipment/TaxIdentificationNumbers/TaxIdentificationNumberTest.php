<?php

declare(strict_types=1);

namespace Tests\Shipment\Items;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Shipment\Items\Item;
use MyParcelCom\Integration\Shipment\Price;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\Enums\TaxNumberTypeEnum;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumber;
use PHPUnit\Framework\TestCase;
use function random_int;

class TaxIdentificationNumberTest extends TestCase
{
    public function test_it_should_return_full_item_with_all_inputs(): void
    {
        $faker = Factory::create();
        $countryCode = $faker->countryCode;
        $type = new TaxNumberTypeEnum('eori');
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
}
