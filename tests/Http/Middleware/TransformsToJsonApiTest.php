<?php

declare(strict_types=1);

namespace Tests\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use MyParcelCom\Integration\Http\Middleware\TransformsToJsonApi;
use MyParcelCom\Integration\Shipment\Shipment;
use PHPUnit\Framework\TestCase;
use stdClass;

class TransformsToJsonApiTest extends TestCase
{
    public function test_it_should_not_transform_if_response_is_not_json(): void
    {
        $middleware = new TransformsToJsonApi();

        $next = static fn() => new stdClass();

        $requestMock = Mockery::mock(Request::class);

        self::assertNotInstanceOf(JsonResponse::class, $middleware->handle($requestMock, $next));
    }

    public function test_it_should_not_transform_if_original_content_does_not_have_shipments(): void
    {
        $middleware = new TransformsToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => new stdClass(),
        ]);

        $next = static fn() => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertSame($responseMock, $middleware->handle($requestMock, $next));
    }

    public function test_it_should_transform_if_original_content_have_shipments(): void
    {
        $middleware = new TransformsToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => [
                'items'         => [
                    Mockery::mock(Shipment::class, [
                        'transformToJsonApiArray' => [],
                    ]),
                ],
                'total_records' => 1,
                'total_pages'   => 1,
            ],
        ]);

        $next = static fn() => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertEquals([
            'data' => [
                [],
            ],
            'meta' => [
                'total_records' => 1,
                'total_pages'   => 1,
            ],
        ], $middleware->handle($requestMock, $next)->getOriginalContent());
    }

    public function test_it_should_transform_if_original_content_is_empty(): void
    {
        $middleware = new TransformsToJsonApi();

        $responseMock = Mockery::mock(JsonResponse::class, [
            'getOriginalContent' => [],
        ]);

        $next = static fn() => $responseMock;

        $requestMock = Mockery::mock(Request::class);

        self::assertEquals([
            'data' => [],
        ], $middleware->handle($requestMock, $next)->getOriginalContent());
    }
}
