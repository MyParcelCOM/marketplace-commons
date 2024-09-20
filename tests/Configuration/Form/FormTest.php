<?php

declare(strict_types=1);

namespace Tests\Configuration\Form;

use Faker\Factory;
use MyParcelCom\Integration\Configuration\Form\Form;
use MyParcelCom\Integration\Configuration\Form\Text;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function test_get_required_property_names(): void
    {
        $faker = Factory::create();

        [$nameOne, $nameTwo] = [$faker->word, $faker->word];
        $description = $faker->words(asText: true);

        $propertyOne = new Text(
            name: $nameOne,
            description: $description,
        );
        $propertyTwo = new Text(
            name: $nameTwo,
            description: $description,
            isRequired: true,
        );

        $form = new Form($propertyOne, $propertyTwo);

        self::assertEquals([$nameTwo], $form->getRequired());
    }
}
