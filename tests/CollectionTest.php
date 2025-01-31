<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use MyParcelCom\Integration\Collection;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertSame;

class CollectionTest extends TestCase
{
    public function test_it_should_convert_items_collection_to_array(): void
    {
        $faker = Factory::create();
        $description1 = $faker->text(20);
        $description2 = $faker->text(20);

        $items = new Collection(
            new class($description1) {
                public function __construct(
                    private readonly string $description,
                ) {
                }

                public function toArray(): array
                {
                    return [
                        'description' => $this->description,
                    ];
                }
            },
            new class($description2) {
                public function __construct(
                    private readonly string $description,
                ) {
                }

                public function toArray(): array
                {
                    return [
                        'description' => $this->description,
                    ];
                }
            },
        );

        assertSame([
            [
                'description' => $description1,
            ],
            [
                'description' => $description2,
            ],
        ], $items->toArray());
    }
}
