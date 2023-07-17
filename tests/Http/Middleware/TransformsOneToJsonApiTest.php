<?php

declare(strict_types=1);

namespace Tests\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Http\Middleware\TransformsOneToJsonApi;
use MyParcelCom\Integration\ProvidesJsonAPI;
use PHPUnit\Framework\TestCase;
use stdClass;

class TransformsOneToJsonApiTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_should_not_transform_if_response_is_not_json(): void
    {
        $middleware = new TransformsOneToJsonApi();

        $next = static fn () => new stdClass();

        $requestMock = Mockery::mock(Request::class);

        self::assertNotInstanceOf(JsonResponse::class, $middleware->handle($requestMock, $next));
    }

    public function test_it_should_return_empty_data_if_origin_content_is_empty(): void
    {
        $middleware = new TransformsOneToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => null,
        ]);

        $next = static fn () => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertEquals(['data' => []], $middleware->handle($requestMock, $next)->getData(true));
    }

    public function test_it_should_not_transform_if_original_content_does_not_have_json_api_object(): void
    {
        $middleware = new TransformsOneToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => new stdClass(),
        ]);

        $next = static fn () => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertSame($responseMock, $middleware->handle($requestMock, $next));
    }

    public function test_it_should_transform_if_original_content_have_json_api_objects(): void
    {
        $middleware = new TransformsOneToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => Mockery::mock(ProvidesJsonAPI::class, [
                'transformToJsonApiArray' => [
                    'test' => 'test',
                ],
            ]),
        ]);

        $next = static fn () => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertEquals([
            'data' => [
                'test' => 'test',
            ],
        ], $middleware->handle($requestMock, $next)->getOriginalContent());
    }
}
