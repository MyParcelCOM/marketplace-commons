<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

class TaxIdentificationNumber
{
    public function __construct(
        public readonly string $countryCode,
        public readonly TaxNumberType $type,
        public readonly string $number,
        public readonly ?string $description = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'country_code' => $this->countryCode,
            'number'       => $this->number,
            'type'         => $this->type->value,
            'description'  => $this->description,
        ]);
    }
}
