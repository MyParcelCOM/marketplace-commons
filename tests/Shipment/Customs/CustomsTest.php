<?php

declare(strict_types=1);

namespace Tests\Shipment\Customs;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Shipment\Customs\ContentType;
use MyParcelCom\Integration\Shipment\Customs\Customs;
use MyParcelCom\Integration\Shipment\Customs\Incoterm;
use MyParcelCom\Integration\Shipment\Customs\NonDelivery;
use PHPUnit\Framework\TestCase;

class CustomsTest extends TestCase
{
    public function test_it_should_convert_customs_object_into_array(): void
    {
        $faker = Factory::create();
        $certificateNumber = $faker->text(20);
        $licenseNumber = $faker->text(15);
        $invoiceNumber = $faker->text(10);

        $customs = new Customs(
            contentType: ContentType::MERCHANDISE,
            invoiceNumber: $invoiceNumber,
            nonDelivery: NonDelivery::RETURN,
            incoterm: Incoterm::DAP,
            shippingValue: Mockery::mock(Price::class, ['toArray' => ['amount' => 1, 'currency' => 'USD']]),
            licenseNumber: $licenseNumber,
            certificateNumber: $certificateNumber,
        );

        self::assertEquals([
            'content_type'       => ContentType::MERCHANDISE->value,
            'invoice_number'     => $invoiceNumber,
            'non_delivery'       => NonDelivery::RETURN->value,
            'incoterm'           => Incoterm::DAP->value,
            'shipping_value'     => ['amount' => 1, 'currency' => 'USD'],
            'license_number'     => $licenseNumber,
            'certificate_number' => $certificateNumber,
        ], $customs->toArray());
    }

    public function test_it_should_convert_empty_customs_object_into_empty_array(): void
    {
        $customs = new Customs();

        self::assertEquals([], $customs->toArray());
    }
}
