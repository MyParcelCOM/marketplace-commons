<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Form\Checkbox;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class CheckboxTest extends TestCase
{
    public function test_it_converts_a_boolean_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word();
        $label = $faker->words(asText: true);

        $checkbox = new Checkbox(
            name: $name,
            label: $label,
        );

        assertEquals([
            $name => [
                'type'        => 'boolean',
                'description' => $label,
            ],
        ], $checkbox->toJsonSchemaProperty()->toArray());
    }
}
