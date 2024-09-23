<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Form\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    public function test_it_converts_a_text_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $text = new Text(
            name: $name,
            description: $description,
        );

        self::assertEquals([
            $name => [
                'type'        => 'string',
                'description' => $description,
            ],
        ], $text->toJsonSchemaProperty()->toArray());
    }
}
