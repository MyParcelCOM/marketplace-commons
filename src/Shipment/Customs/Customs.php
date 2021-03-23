<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Customs;

use MyParcelCom\Integration\Shipment\Price;

class Customs
{
    public function __construct(
        private ContentType $contentType,
        private string $invoiceNumber,
        private NonDelivery $nonDelivery,
        private Incoterm $incoterm,
        private Price $shippingValue,
        private string $licenseNumber,
        private string $certificateNumber,
    ) {
    }

    public function toArray(): array
    {
        return [
            'content_type'       => $this->contentType->getValue(),
            'invoice_number'     => $this->invoiceNumber,
            'non_delivery'       => $this->nonDelivery->getValue(),
            'incoterm'           => $this->incoterm->getValue(),
            'shipping_value'     => $this->shippingValue->toArray(),
            'license_number'     => $this->licenseNumber,
            'certificate_number' => $this->certificateNumber,
        ];
    }
}
