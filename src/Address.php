<?php

declare(strict_types=1);

namespace MyParcelCom\Integration;

class Address
{
    public function __construct(
        public readonly string $street1,
        public readonly string $city,
        public readonly string $countryCode,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $street2 = null,
        public readonly ?int $streetNumber = null,
        public readonly ?string $streetNumberSuffix = null,
        public readonly ?string $postalCode = null,
        public readonly ?string $stateCode = null,
        public readonly ?string $company = null,
        public readonly ?string $email = null,
        public readonly ?string $phoneNumber = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'street_1'             => $this->street1,
            'street_2'             => $this->street2,
            'street_number'        => $this->streetNumber,
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
