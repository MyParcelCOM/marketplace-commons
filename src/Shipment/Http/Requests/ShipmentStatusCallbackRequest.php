<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use MyParcelCom\Integration\Exceptions\RequestInputException;
use MyParcelCom\Integration\Shop\ShopId;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class ShipmentStatusCallbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    public function getShipmentData(): array
    {
        $included = $this->get('included');

        return collect($included)->first(fn ($include) => $include['type'] === 'shipments');
    }

    public function getStatusData(): array
    {
        $included = $this->get('included');

        return collect($included)->first(fn ($include) => $include['type'] === 'statuses');
    }

    public function shopId(): ShopId
    {
        $shopId = Arr::get($this->getShipmentData(), 'relationships.shop.data.id');

        if (!$shopId) {
            throw new RequestInputException('Bad request', 'No shop_id provided in the request body');
        }

        try {
            $shopUuid = Uuid::fromString($shopId);
        } catch (InvalidUuidStringException $exception) {
            throw new RequestInputException('Unprocessable entity', 'shop_id is not a valid UUID', 422);
        }

        return new ShopId($shopUuid);
    }
}
