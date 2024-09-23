<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Form\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function test_it_converts_a_password_property_into_an_array(): void
    {
        $faker = Factory::create();

        $name = $faker->word;
        $description = $faker->words(asText: true);

        $password = new Password(
            name: $name,
            description: $description,
        );

        self::assertEquals([
            $name => [
                'type'        => 'string',
                'description' => $description,
                'meta'        => [
                    'password' => true,
                ],
            ],
        ], $password->toJsonSchemaProperty()->toArray());
    }
}
