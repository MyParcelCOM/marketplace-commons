<?php

declare(strict_types=1);

namespace Tests\Configuration;

use Faker\Factory;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Configuration\Http\Responses\ConfigurationResponse;
use MyParcelCom\JsonSchema\FormBuilder\Form\Checkbox;
use MyParcelCom\JsonSchema\FormBuilder\Form\Form;
use MyParcelCom\JsonSchema\FormBuilder\Form\Number;
use MyParcelCom\JsonSchema\FormBuilder\Form\Option;
use MyParcelCom\JsonSchema\FormBuilder\Form\OptionCollection;
use MyParcelCom\JsonSchema\FormBuilder\Form\Password;
use MyParcelCom\JsonSchema\FormBuilder\Form\RadioButtons;
use MyParcelCom\JsonSchema\FormBuilder\Form\Text;
use MyParcelCom\JsonSchema\FormBuilder\Values\Value;
use MyParcelCom\JsonSchema\FormBuilder\Values\ValueCollection;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class ConfigurationResponseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_creates_a_configuration_response_with_minimal_data(): void
    {
        $form = new Form();
        $configuration = new ConfigurationResponse($form);

        assertEquals(
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

    public function test_it_creates_a_configuration_response_with_values(): void
    {
        $faker = Factory::create();

        $form = new Form();
        $name = $faker->word();
        $value = $faker->word();

        $valueObject = new Value($name, $value);

        $configuration = new ConfigurationResponse($form, new ValueCollection($valueObject));

        assertEquals(
            [
                'configuration_schema' => [
                    '$schema'              => 'http://json-schema.org/draft-04/schema#',
                    'additionalProperties' => false,
                    'required'             => [],
                    'properties'           => [],
                ],
                'values'               => [
                    [
                        'name'  => $name,
                        'value' => $value,
                    ],
                ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }

    public function test_it_creates_a_configuration_response_with_all_data(): void
    {
        $faker = Factory::create();

        [$nameText, $nameNumber, $nameCheckbox, $namePassword, $nameSelect] = [
            $faker->word(),
            $faker->word(),
            $faker->word(),
            $faker->word(),
            $faker->word(),
        ];
        $label = $faker->words(asText: true);

        $text = new Text(
            name: $nameText,
            label: $label,
            isRequired: true,
        );
        $number = new Number(
            name: $nameNumber,
            label: $label,
            isRequired: true,
        );
        $checkbox = new Checkbox(
            name: $nameCheckbox,
            label: $label,
            isRequired: true,
            help: 'A help for this property',
        );
        $password = new Password(
            name: $namePassword,
            label: $label,
            isRequired: true,
        );
        $select = new RadioButtons(
            name: $nameSelect,
            label: $label,
            options: new OptionCollection(
                new Option('1'),
                new Option('2'),
                new Option('3'),
            ),
            isRequired: true,
        );
        $form = new Form(
            $text,
            $number,
            $checkbox,
            $password,
            $select,
        );

        $name = $faker->word();
        $value = $faker->word();

        $valueObject = new Value($name, $value);

        $configuration = new ConfigurationResponse($form, new ValueCollection($valueObject));

        assertEquals(
            [
                'configuration_schema' => [
                    '$schema'              => 'http://json-schema.org/draft-04/schema#',
                    'additionalProperties' => false,
                    'required'             => [$nameText, $nameNumber, $nameCheckbox, $namePassword, $nameSelect],
                    'properties'           => [
                        $nameText     => [
                            'type'        => 'string',
                            'description' => $label,
                        ],
                        $nameNumber   => [
                            'type'        => 'number',
                            'description' => $label,
                        ],
                        $nameCheckbox => [
                            'type'        => 'boolean',
                            'description' => $label,
                            'meta'        => [
                                'help' => 'A help for this property',
                            ],
                        ],
                        $namePassword => [
                            'type'        => 'string',
                            'description' => $label,
                            'meta'        => [
                                'password' => true,
                            ],
                        ],
                        $nameSelect   => [
                            'type'        => 'string',
                            'description' => $label,
                            'enum'        => [
                                '1',
                                '2',
                                '3',
                            ],
                            'meta' => ['field_type' => 'radio']
                        ],
                    ],
                ],
                'values'               => [
                    [
                        'name'  => $name,
                        'value' => $value,
                    ],
                ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }
}
