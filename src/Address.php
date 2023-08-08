<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Address
{
    public function __construct(
        private readonly string $street1,
        private readonly string $city,
        private readonly string $countryCode,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly ?string $street2 = null,
        private readonly ?int $streetNumber = null,
        private readonly ?string $streetNumberSuffix = null,
        private readonly ?string $postalCode = null,
        private readonly ?string $stateCode = null,
        private readonly ?string $company = null,
        private readonly ?string $email = null,
        private readonly ?string $phoneNumber = null,
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
