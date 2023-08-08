<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyParcelCom\Integration\Price;

class Customs
{
    public function __construct(
        private readonly ?ContentType $contentType = null,
        private readonly ?string $invoiceNumber = null,
        private readonly ?NonDelivery $nonDelivery = null,
        private readonly ?Incoterm $incoterm = null,
        private readonly ?Price $shippingValue = null,
        private readonly ?string $licenseNumber = null,
        private readonly ?string $certificateNumber = null,
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
