<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyParcelCom\Integration\Price;

class Customs
{
    public function __construct(
        private ?ContentType $contentType = null,
        private ?string $invoiceNumber = null,
        private ?NonDelivery $nonDelivery = null,
        private ?Incoterm $incoterm = null,
        private ?Price $shippingValue = null,
        private ?string $licenseNumber = null,
        private ?string $certificateNumber = null,
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
