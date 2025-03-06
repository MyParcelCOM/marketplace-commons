<?php

declare(strict_types=1);

namespace Tests\Shipment;

use Faker\Factory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\Shipment\PickUpLocation;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class PickUpLocationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_converts_pickup_location_to_array(): void
    {
        $address = Mockery::mock(Address::class);
        $address
            ->expects('toArray')
            ->andReturn([
                'street_1'             => 'My Street',
                'street_2'             => 'addition',
                'street_number'        => 1,
                'street_number_suffix' => 'A',
                'postal_code'          => '1234AB',
                'city'                 => 'My City',
                'state_code'           => 'SC',
                'country_code'         => 'NL',
                'first_name'           => 'John',
                'last_name'            => 'Doe',
                'company'              => 'My Company',
                'email'                => 'test@example.com',
                'phone_number'         => '1234567890',
            ]);

        $pickupLocation = new PickUpLocation('code', $address);

        assertEquals([
            'code'    => 'code',
            'address' => [
                'street_1'             => 'My Street',
                'street_2'             => 'addition',
                'street_number'        => 1,
                'street_number_suffix' => 'A',
                'postal_code'          => '1234AB',
                'city'                 => 'My City',
                'state_code'           => 'SC',
                'country_code'         => 'NL',
                'first_name'           => 'John',
                'last_name'            => 'Doe',
                'company'              => 'My Company',
                'email'                => 'test@example.com',
                'phone_number'         => '1234567890',
            ],
        ], $pickupLocation->toArray());
    }
}
