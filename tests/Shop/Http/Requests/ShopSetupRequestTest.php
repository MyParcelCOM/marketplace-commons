<?php

declare(strict_types=1);

namespace Tests\Shop\Http\Requests;

use Faker\Factory;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Shop\Http\Requests\ShopSetupRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class ShopSetupRequestTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public static function success_data(): array
    {
        $faker = Factory::create();

        return [
            [
                [
                    'data' => [
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'mp_client'       => ['id' => $faker->uuid(), 'secret' => $faker->password()],
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'mp_client'       => ['id' => $faker->uuid(), 'secret' => $faker->password()],
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
        ];
    }

    public static function failure_data(): array
    {
        $faker = Factory::create();

        return [
            // Missing mp_client.secret
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'mp_client'       => ['id' => $faker->uuid()],
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            // Missing mp_client.id
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'mp_client'       => ['secret' => $faker->password()],
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            // Missing mp_client.id and mp_client.secret
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'mp_client'       => [],
                        'organization_id' => $faker->uuid(),
                        'broker_id'       => $faker->uuid(),
                    ],
                ],
            ],
            // Missing organization_id
            [
                [
                    'data' => [
                        'settings'     => [
                            'foo' => 'bar',
                        ],
                        'redirect_url' => $faker->url(),
                        'mp_client'    => ['id' => $faker->uuid(), 'secret' => $faker->password()],
                        'broker_id'    => $faker->uuid(),
                    ],
                ],
            ],
            // Missing broker_id
            [
                [
                    'data' => [
                        'settings'        => [
                            'foo' => 'bar',
                        ],
                        'redirect_url'    => $faker->url(),
                        'mp_client'       => ['id' => $faker->uuid(), 'secret' => $faker->password()],
                        'organization_id' => $faker->uuid(),
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('success_data')]
    public function test_validation_rules_pass(array $data): void
    {
        $request = new ShopSetupRequest();
        $rules = $request->rules();

        $translator = Mockery::mock(Translator::class);
        $translator->expects('get')->never();

        $validator = new Validator(
            $translator,
            $data,
            $rules,
        );

        assertTrue($validator->passes());
    }

    #[DataProvider('failure_data')]
    public function test_validation_rules_fail(array $data): void
    {
        $request = new ShopSetupRequest();
        $rules = $request->rules();

        $translator = Mockery::mock(Translator::class);
        $translator->allows('get')->andReturn('test');

        $validator = new Validator(
            $translator,
            $data,
            $rules,
        );

        assertTrue($validator->fails());
    }
}
