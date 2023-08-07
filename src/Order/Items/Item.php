<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Order\Items;

use MyParcelCom\Integration\Data;
use MyParcelCom\Integration\Price;
use MyParcelCom\Integration\Weight;
use MyParcelCom\Integration\WeightUnit;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class Item extends Data
{
    protected static string $_collectionClass = ItemCollection::class;

    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        public int $quantity,
        public readonly Price $itemPrice,
        public ?string $sku = null,
        public ?string $imageUrl = null,
        public readonly ?Weight $itemWeight = null,
        public readonly ?array $features = null,
    ) {
    }
}
