<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\TaxIdentificationNumbers;

class TaxIdentificationNumber
{
    public function __construct(
        private readonly string $countryCode,
        private readonly TaxNumberType $type,
        private readonly string $number,
        private readonly ?string $description = null,
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
