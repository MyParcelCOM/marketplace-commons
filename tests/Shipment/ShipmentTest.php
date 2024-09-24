<?php

declare(strict_types=1);

namespace Tests\Shipment;

use Faker\Factory;
use Mockery;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\PhysicalProperties;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Shipment\Customs\Customs;
use MyParcelCom\Integration\Shipment\Exception\InvalidChannelException;
use MyParcelCom\Integration\Shipment\Exception\InvalidTagException;
use MyParcelCom\Integration\Shipment\Items\ItemCollection;
use MyParcelCom\Integration\Shipment\Shipment;
use MyParcelCom\Integration\Shipment\TaxIdentificationNumbers\TaxIdentificationNumberCollection;
use MyParcelCom\Integration\Shop\ShopId;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{
    public function test_it_should_throw_exception_when_tags_contain_non_strings(): void
    {
        $this->expectException(InvalidTagException::class);

        new Shipment(
            shopId: Mockery::mock(ShopId::class),
            recipientAddress: Mockery::mock(Address::class),
            description: '',
            customerReference: '',
            channel: 'test',
            totalValue: Mockery::mock(Price::class),
            price: Mockery::mock(Price::class),
            physicalProperties: Mockery::mock(PhysicalProperties::class),
            items: Mockery::mock(ItemCollection::class),
            tags: [1, 2, 3],
        );
    }

    public function test_it_should_throw_exception_when_channel_is_empty(): void
    {
        $this->expectException(InvalidChannelException::class);

        new Shipment(
            shopId: Mockery::mock(ShopId::class),
            recipientAddress: Mockery::mock(Address::class),
            description: '',
            customerReference: '',
            channel: '',
            totalValue: Mockery::mock(Price::class),
            price: Mockery::mock(Price::class),
            physicalProperties: Mockery::mock(PhysicalProperties::class),
            items: Mockery::mock(ItemCollection::class),
            tags: [],
        );
    }

    public function test_it_should_transform_shipment_to_json_api_with_minimum_requirements(): void
    {
        $faker = Factory::create();

        $shopUuid = $faker->uuid();
        $shopIdMock = Mockery::mock(ShopId::class, ['toString' => $shopUuid]);

        $street1 = $faker->streetAddress();
        $city = $faker->city();
        $countryCode = $faker->countryCode();
        $firstName = $faker->firstName();
        $lastName = $faker->lastName();
        $amount = random_int(100, 900);
        $currencyCode = $faker->currencyCode();
        $weight = random_int(100, 300);

        $addressMock = Mockery::mock(Address::class, [
            'toArray' => [
                'street_1'     => $street1,
                'city'         => $city,
                'country_code' => $countryCode,
                'first_name'   => $firstName,
                'last_name'    => $lastName,
            ],
        ]);

        $priceMock = Mockery::mock(Price::class, [
            'toArray' => [
                'amount'   => $amount,
                'currency' => $currencyCode,
            ],
        ]);

        $physicalPropertiesMock = Mockery::mock(PhysicalProperties::class, [
            'toArray' => [
                'weight' => $weight,
            ],
        ]);

        $itemsMock = Mockery::mock(ItemCollection::class, ['toArray' => []]);

        $channel = $faker->text(5);
        $customerReference = $faker->text(16);
        $description = $faker->text(15);

        $shipment = new Shipment(
            shopId: $shopIdMock,
            recipientAddress: $addressMock,
            description: $description,
            customerReference: $customerReference,
            channel: $channel,
            totalValue: $priceMock,
            price: $priceMock,
            physicalProperties: $physicalPropertiesMock,
            items: $itemsMock,
        );

        self::assertEquals([
            'type'          => 'shipments',
            'attributes'    => [
                'recipient_address'   => [
                    'street_1'     => $street1,
                    'city'         => $city,
                    'country_code' => $countryCode,
                    'first_name'   => $firstName,
                    'last_name'    => $lastName,
                ],
                'description'         => $description,
                'customer_reference'  => $customerReference,
                'channel'             => $channel,
                'total_value'         => [
                    'amount'   => $amount,
                    'currency' => $currencyCode,
                ],
                'price'               => [
                    'amount'   => $amount,
                    'currency' => $currencyCode,
                ],
                'physical_properties' => [
                    'weight' => $weight,
                ],
            ],
            'relationships' => [
                'shop' => [
                    'data' => [
                        'type' => 'shops',
                        'id'   => $shopUuid,
                    ],
                ],
            ],
        ], $shipment->transformToJsonApiArray());
    }

    public function test_it_should_transform_shipment_to_json_api_with_all_inputs(): void
    {
        $faker = Factory::create();

        $shopUuid = $faker->uuid();
        $shopIdMock = Mockery::mock(ShopId::class, ['toString' => $shopUuid]);

        $recipientStreet1 = $faker->streetAddress();
        $recipientCity = $faker->city();
        $recipientCountryCode = $faker->countryCode();
        $recipientFirstName = $faker->firstName();
        $recipientLastName = $faker->lastName();

        $recipientAddressMock = Mockery::mock(Address::class, [
            'toArray' => [
                'street_1'     => $recipientStreet1,
                'city'         => $recipientCity,
                'country_code' => $recipientCountryCode,
                'first_name'   => $recipientFirstName,
                'last_name'    => $recipientLastName,
            ],
        ]);

        $senderStreet1 = $faker->streetAddress();
        $senderCity = $faker->city();
        $senderCountryCode = $faker->countryCode();
        $senderFirstName = $faker->firstName();
        $senderLastName = $faker->lastName();

        $senderAddressMock = Mockery::mock(Address::class, [
            'toArray' => [
                'street_1'     => $senderStreet1,
                'city'         => $senderCity,
                'country_code' => $senderCountryCode,
                'first_name'   => $senderFirstName,
                'last_name'    => $senderLastName,
            ],
        ]);

        $returnStreet1 = $faker->streetAddress();
        $returnCity = $faker->city();
        $returnCountryCode = $faker->countryCode();
        $returnFirstName = $faker->firstName();
        $returnLastName = $faker->lastName();

        $returnAddressMock = Mockery::mock(Address::class, [
            'toArray' => [
                'street_1'     => $returnStreet1,
                'city'         => $returnCity,
                'country_code' => $returnCountryCode,
                'first_name'   => $returnFirstName,
                'last_name'    => $returnLastName,
            ],
        ]);

        $amount = random_int(100, 900);
        $currencyCode = $faker->currencyCode();
        $priceMock = Mockery::mock(Price::class, [
            'toArray' => [
                'amount'   => $amount,
                'currency' => $currencyCode,
            ],
        ]);

        $weight = random_int(100, 300);
        $physicalPropertiesMock = Mockery::mock(PhysicalProperties::class, [
            'toArray' => [
                'weight' => $weight,
            ],
        ]);

        $itemsMock = Mockery::mock(ItemCollection::class, ['toArray' => []]);

        $channel = $faker->text(5);
        $customerReference = $faker->text(16);
        $description = $faker->text(15);

        $tag = $faker->word();

        $createdAt = $faker->dateTime();
        $customsMock = Mockery::mock(Customs::class, [
            'toArray' => [
                'content_type'       => 'test',
                'invoice_number'     => 'test2',
                'non_delivery'       => 'test3',
                'incoterm'           => 'DAP',
                'license_number'     => 'test5',
                'certificate_number' => 'test6',
            ],
        ]);

        $taxNumberCollectionMock = Mockery::mock(TaxIdentificationNumberCollection::class, [
            'toArray' => [
                [
                    'country_code' => 'UK',
                    'Description'  => 'The IOSS number for the Kazan company',
                    'number'       => 'KSI818298',
                    'type'         => 'ioss',
                ],
            ],
        ]);

        $shipment = new Shipment(
            shopId: $shopIdMock,
            createdAt: $createdAt,
            recipientAddress: $recipientAddressMock,
            senderAddress: $senderAddressMock,
            returnAddress: $returnAddressMock,
            description: $description,
            customerReference: $customerReference,
            channel: $channel,
            totalValue: $priceMock,
            price: $priceMock,
            physicalProperties: $physicalPropertiesMock,
            items: $itemsMock,
            senderTaxIdentificationNumbers: $taxNumberCollectionMock,
            recipientTaxIdentificationNumbers: $taxNumberCollectionMock,
            customs: $customsMock,
            tags: [$tag],
        );

        self::assertEquals([
            'type'          => 'shipments',
            'attributes'    => [
                'created_at'                           => $createdAt->getTimestamp(),
                'recipient_address'                    => [
                    'street_1'     => $recipientStreet1,
                    'city'         => $recipientCity,
                    'country_code' => $recipientCountryCode,
                    'first_name'   => $recipientFirstName,
                    'last_name'    => $recipientLastName,
                ],
                'sender_address'                       => [
                    'street_1'     => $senderStreet1,
                    'city'         => $senderCity,
                    'country_code' => $senderCountryCode,
                    'first_name'   => $senderFirstName,
                    'last_name'    => $senderLastName,
                ],
                'return_address'                       => [
                    'street_1'     => $returnStreet1,
                    'city'         => $returnCity,
                    'country_code' => $returnCountryCode,
                    'first_name'   => $returnFirstName,
                    'last_name'    => $returnLastName,
                ],
                'description'                          => $description,
                'customer_reference'                   => $customerReference,
                'channel'                              => $channel,
                'total_value'                          => [
                    'amount'   => $amount,
                    'currency' => $currencyCode,
                ],
                'price'                                => [
                    'amount'   => $amount,
                    'currency' => $currencyCode,
                ],
                'physical_properties'                  => [
                    'weight' => $weight,
                ],
                'customs'                              => [
                    'content_type'       => 'test',
                    'invoice_number'     => 'test2',
                    'non_delivery'       => 'test3',
                    'incoterm'           => 'DAP',
                    'license_number'     => 'test5',
                    'certificate_number' => 'test6',
                ],
                'sender_tax_identification_numbers'    => [
                    [
                        'country_code' => 'UK',
                        'Description'  => 'The IOSS number for the Kazan company',
                        'number'       => 'KSI818298',
                        'type'         => 'ioss',
                    ],
                ],
                'recipient_tax_identification_numbers' => [
                    [
                        'country_code' => 'UK',
                        'Description'  => 'The IOSS number for the Kazan company',
                        'number'       => 'KSI818298',
                        'type'         => 'ioss',
                    ],
                ],
                'tags'                                 => [$tag],
            ],
            'relationships' => [
                'shop' => [
                    'data' => [
                        'type' => 'shops',
                        'id'   => $shopUuid,
                    ],
                ],
            ],
        ], $shipment->transformToJsonApiArray());
    }
}
