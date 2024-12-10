<?php

declare(strict_types=1);

namespace Tests\Order\Items;

use Faker\Factory;
use MyParcelCom\Integration\Order\Items\Annotation;
use MyParcelCom\Integration\Order\Items\Feature;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class FeatureTest extends TestCase
{
    public function test_it_converts_feature_into_array_without_annotation(): void
    {
        $faker = Factory::create();

        $key = $faker->word();
        $value = $faker->randomElement(
            [$faker->word(), $faker->boolean, $faker->randomNumber(2), $faker->randomFloat()],
        );

        $feature = new Feature(
            key: $key,
            value: $value,
        );

        assertEquals([
            'key'   => $key,
            'value' => $value,
        ], $feature->toArray());
    }

    public function test_it_converts_feature_into_array_with_annotation(): void
    {
        $faker = Factory::create();

        $key = $faker->word();
        $value = $faker->randomElement(
            [$faker->word, $faker->boolean, $faker->randomNumber(2), $faker->randomFloat()],
        );
        /** @var Annotation $annotation */
        $annotation = $faker->randomElement(Annotation::cases());

        $feature = new Feature(
            key: $key,
            value: $value,
            annotation: $annotation,
        );

        assertEquals([
            'key'        => $key,
            'value'      => $value,
            'annotation' => $annotation->value,
        ], $feature->toArray());
    }
}
