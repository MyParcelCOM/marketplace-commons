<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shop;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * @implements CastsAttributes<ShopId,ShopId>
 */
class ShopIdCaster implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ShopId
    {
        return new ShopId(Uuid::fromString($value));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return $value->toString();
    }
}
