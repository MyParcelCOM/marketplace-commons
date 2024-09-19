<?php

declare(strict_types=1);

namespace Tests\Configuration;

use DateTimeInterface;
use Faker\Factory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Address;
use MyParcelCom\Integration\Configuration\Configuration;
use MyParcelCom\Integration\Configuration\Properties\PropertyCollection;
use MyParcelCom\Integration\Order\Items\ItemCollection;
use MyParcelCom\Integration\Order\Order;
use MyParcelCom\Integration\Shop\ShopId;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_convert_a_configuration_with_minimal_properties_to_json_api_array(): void
    {
        $properties = Mockery::mock(PropertyCollection::class, [
            'toArray' => [],
        ]);

        $configuration = new Configuration($properties);

        self::assertEquals([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'additionalProperties' => false,
            'required'             => [], // ???
            'properties'           => [],
        ], $configuration->transformToJsonApiArray());
    }

    public function test_it_convert_a_configuration_with_all_properties_to_json_api_array(): void
    {
        $properties = Mockery::mock(PropertyCollection::class, [
            'toArray' => [
                [
                    'propertyZero' => [
                        'type' => 'string',
                    ],
                ],
                [
                    'propertyOne' => [
                        'type'        => 'string',
                        'description' => 'A string property',
                    ],
                ],
                [
                    'propertyTwo' => [
                        'type'        => 'number',
                        'description' => 'A number property',
                        'enum'        => [
                            1,
                            2,
                            3,
                        ],
                    ],
                ],
                [
                    'propertyThree' => [
                        'type'        => 'boolean',
                        'description' => 'A boolean property',
                        'meta'        => [
                            'hint' => 'A hint for this property',
                        ],
                    ],
                ],
            ],
        ]);

        $configuration = new Configuration($properties);

        self::assertEquals([
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            'additionalProperties' => false,
            'required'             => [], // ???
            'properties'           => [
                'propertyZero'  => [
                    'type' => 'string',
                ],
                'propertyOne'   => [
                    'type'        => 'string',
                    'description' => 'A string property',
                ],
                'propertyTwo'   => [
                    'type'        => 'number',
                    'description' => 'A number property',
                    'enum'        => [
                        1,
                        2,
                        3,
                    ],
                ],
                'propertyThree' => [
                    'type'        => 'boolean',
                    'description' => 'A boolean property',
                    'meta'        => [
                        'hint' => 'A hint for this property',
                    ],
                ],
            ],
        ], $configuration->transformToJsonApiArray());
    }
}
