<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyParcelCom\Integration\Price;

class Customs
{
    public function __construct(
        public readonly ?ContentType $contentType = null,
        public readonly ?string $invoiceNumber = null,
        public readonly ?NonDelivery $nonDelivery = null,
        public readonly ?Incoterm $incoterm = null,
        public readonly ?Price $shippingValue = null,
        public readonly ?string $licenseNumber = null,
        public readonly ?string $certificateNumber = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'content_type'       => $this->contentType?->value,
            'invoice_number'     => $this->invoiceNumber,
            'non_delivery'       => $this->nonDelivery?->value,
            'incoterm'           => $this->incoterm?->value,
            'shipping_value'     => $this->shippingValue?->toArray(),
            'license_number'     => $this->licenseNumber,
            'certificate_number' => $this->certificateNumber,
        ]);
    }
}
