<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment;

use function array_filter;

class Address
{
    public function __construct(
        private string $street1,
        private ?string $street2 = null,
        private ?int $streetNumber = null,
        private ?string $streetNumberSuffix = null,
        private ?string $postalCode = null,
        private string $city,
        private ?string $stateCode = null,
        private string $countryCode,
        private string $firstName,
        private string $lastName,
        private ?string $company = null,
        private ?string $email = null,
        private ?string $phoneNumber = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'street_1'             => $this->street1,
            'street_2'             => $this->street2,
            'street_number'        => $this->streetNumber ? (int) $this->streetNumber : null,
            'street_number_suffix' => $this->streetNumberSuffix,
            'postal_code'          => $this->postalCode,
            'city'                 => $this->city,
            'state_code'           => $this->stateCode,
            'country_code'         => $this->countryCode,
            'first_name'           => $this->firstName,
            'last_name'            => $this->lastName,
            'company'              => $this->company,
            'email'                => $this->email,
            'phone_number'         => $this->phoneNumber,
        ]);
    }
}
