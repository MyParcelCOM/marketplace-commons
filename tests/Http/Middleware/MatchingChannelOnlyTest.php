<?php

declare(strict_types=1);

namespace Tests\Http\Middleware;

use Faker\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Http\Middleware\MatchingChannelOnly;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

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
            ->allows('get')
            ->with('included')
            ->andReturns([
                [
                    'type'       => 'shipments',
                    'attributes' => [
                        'channel' => $shipmentChannel,
                    ],
                ],
            ])
            ->getMock();

        $response = $middleware->handle($requestMock, function (Request $request) {}, $expectedChannel);

        assertInstanceOf(JsonResponse::class, $response);
        assertSame(204, $response->getStatusCode());
    }

    public function test_it_continues_request_when_channels_match(): void
    {
        $faker = Factory::create();
        $middleware = new MatchingChannelOnly();
        $expectedChannel = $shipmentChannel = $faker->word();

        /** @var Request $requestMock */
        $requestMock = Mockery::mock(Request::class)
            ->allows('get')
            ->with('included')
            ->andReturns([
                [
                    'type'       => 'shipments',
                    'attributes' => [
                        'channel' => $shipmentChannel,
                    ],
                ],
            ])
            ->getMock();

        $responseMock = Mockery::mock(Response::class);

        $response = $middleware->handle($requestMock, fn (Request $request) => $responseMock, $expectedChannel);

        assertSame($responseMock, $response);
    }
}
