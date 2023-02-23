<?php

declare(strict_types=1);

namespace Tests\Http\Middleware;

use Closure;
use Faker\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Http\Middleware\MatchingChannelOnly;
use PHPUnit\Framework\TestCase;

class MatchingChannelOnlyTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_responds_with_204_when_channels_do_not_match(): void
    {
        $faker = Factory::create();
        $middleware = new MatchingChannelOnly();
        $expectedChannel = $faker->word();
        $shipmentChannel = $faker->word();

        /** @var Request $requestMock */
        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('get')
            ->with('included')
            ->andReturn([
                [
                    'type' => 'shipments',
                    'attributes' => [
                        'channel' => $shipmentChannel
                    ]
                ]
            ])
            ->getMock();

        $response = $middleware->handle($requestMock, function (Request $request) {}, $expectedChannel);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(204, $response->getStatusCode());
    }

    public function test_it_continues_request_when_channels_match(): void
    {
        $faker = Factory::create();
        $middleware = new MatchingChannelOnly();
        $expectedChannel = $shipmentChannel = $faker->word();

        /** @var Request $requestMock */
        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('get')
            ->with('included')
            ->andReturn([
                [
                    'type' => 'shipments',
                    'attributes' => [
                        'channel' => $shipmentChannel
                    ]
                ]
            ])
            ->getMock();

        $responseMock = Mockery::mock(Response::class);

        $response = $middleware->handle($requestMock, fn (Request $request) => $responseMock, $expectedChannel);

        self::assertSame($responseMock, $response);
    }
}
