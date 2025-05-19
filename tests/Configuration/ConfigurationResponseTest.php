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
use MyParcelCom\JsonSchema\FormBuilder\Form\Select;
use MyParcelCom\JsonSchema\FormBuilder\Form\Text;
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
                'configuration_schema' => [
                    '$schema'              => 'https://json-schema.org/draft/2020-12/schema',
                    'additionalProperties' => false,
                    'required'             => [],
                    'properties'           => [],
                ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }

    public function test_it_creates_a_configuration_response_with_field_and_value(): void
    {
        $field = new Text(
            name: 'field_name',
            label: 'Field Label',
            value: 'field_value',
        );

        $form = new Form($field);

        $configuration = new ConfigurationResponse($form);

        assertEquals(
            [
                'configuration_schema' => [
                    '$schema'              => 'https://json-schema.org/draft/2020-12/schema',
                    'additionalProperties' => false,
                    'required'             => [],
                    'properties'           => [
                        'field_name' => [
                            'type'        => 'string',
                            'description' => 'Field Label',
                        ],
                    ],
                ],
                'values'               => ['field_name' => 'field_value'],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }

    public function test_it_creates_a_configuration_response_with_all_data(): void
    {
        $text = new Text(
            name: 'text_field',
            label: 'Text Field',
            isRequired: true,
            value: 'text_value',
        );
        $number = new Number(
            name: 'number_field',
            label: 'Number Field',
            isRequired: true,
            value: 23.2,
        );
        $checkbox = new Checkbox(
            name: 'checkbox_field',
            label: 'Checkbox Field',
            isRequired: true,
            help: 'A help for this property',
        );
        $password = new Password(
            name: 'password_field',
            label: 'Password Field',
            isRequired: true,
        );
        $select = new Select(
            name: 'select_field',
            label: 'Select Field',
            options: new OptionCollection(
                new Option('1', 'One'),
                new Option('2', 'Two'),
                new Option('3', 'Three'),
            ),
            value: '3',
        );
        $form = new Form(
            $text,
            $number,
            $checkbox,
            $password,
            $select,
        );


        $configuration = new ConfigurationResponse($form);

        assertEquals(
            [
                'configuration_schema' => [
                    '$schema'              => 'https://json-schema.org/draft/2020-12/schema',
                    'additionalProperties' => false,
                    'required'             => ['text_field', 'number_field', 'checkbox_field', 'password_field'],
                    'properties'           => [
                        'text_field'     => [
                            'type'        => 'string',
                            'description' => 'Text Field',
                        ],
                        'number_field'   => [
                            'type'        => 'number',
                            'description' => 'Number Field',
                        ],
                        'checkbox_field' => [
                            'type'        => 'boolean',
                            'description' => 'Checkbox Field',
                            'meta'        => [
                                'help' => 'A help for this property',
                            ],
                        ],
                        'password_field' => [
                            'type'        => 'string',
                            'description' => 'Password Field',
                            'meta'        => [
                                'password' => true,
                            ],
                        ],
                        'select_field'   => [
                            'type'        => 'string',
                            'description' => 'Select Field',
                            'enum'        => ['1', '2', '3'],
                            'meta'        => [
                                'field_type'  => 'select',
                                'enum_labels' => [
                                    '1' => 'One',
                                    '2' => 'Two',
                                    '3' => 'Three',
                                ],
                            ],
                        ],
                    ],
                ],
                'values'               => [
                    'text_field'   => 'text_value',
                    'number_field' => 23.2,
                    'select_field' => '3',
                ],
            ],
            $configuration->toResponse(Mockery::mock(Request::class))->getData(true),
        );
    }
}
