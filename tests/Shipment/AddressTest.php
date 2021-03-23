<?php

declare(strict_types=1);

namespace Tests\Shipment;

use Faker\Factory;
use MyParcelCom\Integration\Shipment\Address;
use PHPUnit\Framework\TestCase;
use function random_int;

class AddressTest extends TestCase
{
    public function test_it_convert_address_to_array_with_minimum_required_inputs(): void
    {
        $faker = Factory::create();
        $street1 = $faker->streetAddress;
        $city = $faker->city;
        $countryCode = $faker->countryCode;
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;

        $address = new Address(
            street1: $street1,
            city: $city,
            countryCode: $countryCode,
            firstName: $firstName,
            lastName: $lastName,
        );

        self::assertEquals([
            'street_1'     => $street1,
            'city'         => $city,
            'country_code' => $countryCode,
            'first_name'   => $firstName,
            'last_name'    => $lastName,
        ], $address->toArray());
    }

    public function test_it_convert_address_to_array_with_all_inputs(): void
    {
        $faker = Factory::create();
        $street1 = $faker->streetAddress;
        $street2 = $faker->streetAddress;
        $streetNumber = random_int(10, 99);
        $streetNumberSuffix = $faker->streetSuffix;
        $postalCode = $faker->postcode;
        $city = $faker->city;
        $countryCode = $faker->countryCode;
        $firstName = $faker->firstName;
        $lastName = $faker->lastName;
        $stateCode = $faker->countryISOAlpha3;
        $company = $faker->company;
        $email = $faker->email;
        $phoneNumber = $faker->phoneNumber;

        $address = new Address(
            street1: $street1,
            street2: $street2,
            streetNumber: $streetNumber,
            streetNumberSuffix: $streetNumberSuffix,
            postalCode: $postalCode,
            city: $city,
            stateCode: $stateCode,
            countryCode: $countryCode,
            firstName: $firstName,
            lastName: $lastName,
            company: $company,
            email: $email,
            phoneNumber: $phoneNumber,
        );

        self::assertEquals([
            'street_1'             => $street1,
            'street_2'             => $street2,
            'street_number'        => $streetNumber,
            'street_number_suffix' => $streetNumberSuffix,
            'postal_code'          => $postalCode,
            'city'                 => $city,
            'state_code'           => $stateCode,
            'country_code'         => $countryCode,
            'first_name'           => $firstName,
            'last_name'            => $lastName,
            'company'              => $company,
            'email'                => $email,
            'phone_number'         => $phoneNumber,
        ], $address->toArray());
    }
}
