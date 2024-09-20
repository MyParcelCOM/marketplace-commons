<?php

declare(strict_types=1);

namespace Tests\Configuration;

use Faker\Factory;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Configuration\Http\Responses\ConfigurationResponse;
use MyParcelCom\Integration\Configuration\Properties\Checkbox;
use MyParcelCom\Integration\Configuration\Properties\Number;
use MyParcelCom\Integration\Configuration\Properties\Password;
use MyParcelCom\Integration\Configuration\Properties\PropertyCollection;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;
use MyParcelCom\Integration\Configuration\Properties\Select;
use MyParcelCom\Integration\Configuration\Properties\Text;
use PHPUnit\Framework\TestCase;

class ConfigurationResponseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_creates_a_configuration_response_with_minimal_data(): void
    {
        $properties = Mockery::mock(PropertyCollection::class, [
            'toArray'     => [],
            'getRequired' => [],
        ]);

        $configuration = new ConfigurationResponse($properties);

        self::assertEquals(
            [
                'configuration_schema' =>
                    [
                        '$schema'              => 'http://json-schema.org/draft-04/schema#',
                        'additionalProperties' => false,
                        'required'             => [],
                        'properties'           => [],
                    ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }

    public function test_it_creates_a_configuration_response_with_all_data(): void
    {
        $faker = Factory::create();

        [$nameText, $nameNumber, $nameCheckbox, $namePassword, $nameSelect] = [
            $faker->word,
            $faker->word,
            $faker->word,
            $faker->word,
            $faker->word,
        ];
        $description = $faker->words(asText: true);

        $text = new Text(
            name: $nameText,
            description: $description,
            isRequired: true,
        );
        $number = new Number(
            name: $nameNumber,
            description: $description,
            isRequired: true,
        );
        $checkbox = new Checkbox(
            name: $nameCheckbox,
            description: $description,
            isRequired: true,
            hint: 'A hint for this property',
        );
        $password = new Password(
            name: $namePassword,
            description: $description,
            isRequired: true,
        );
        $select = new Select(
            name: $nameSelect,
            type: PropertyType::STRING,
            description: $description,
            enum: ['1', '2', '3'],
            isRequired: true,
        );
        $properties = new PropertyCollection(
            $text,
            $number,
            $checkbox,
            $password,
            $select,
        );

        $configuration = new ConfigurationResponse($properties);

        self::assertEquals(
            [
                'configuration_schema' => [
                    '$schema'              => 'http://json-schema.org/draft-04/schema#',
                    'additionalProperties' => false,
                    'required'             => [$nameText, $nameNumber, $nameCheckbox, $namePassword, $nameSelect],
                    'properties'           => [
                        $nameText     => [
                            'type'        => 'string',
                            'description' => $description,
                        ],
                        $nameNumber   => [
                            'type'        => 'number',
                            'description' => $description,
                        ],
                        $nameCheckbox => [
                            'type'        => 'boolean',
                            'description' => $description,
                            'meta'        => [
                                'hint' => 'A hint for this property',
                            ],
                        ],
                        $namePassword => [
                            'type'        => 'string',
                            'description' => $description,
                            'meta'        => [
                                'password' => true,
                            ],
                        ],
                        $nameSelect   => [
                            'type'        => 'string',
                            'description' => $description,
                            'enum'        => [
                                '1',
                                '2',
                                '3',
                            ],
                        ],
                    ],
                ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }
}
