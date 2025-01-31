<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Exceptions\Handler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class HandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private Handler $handler;
    private ResponseFactory $responseFactoryMock;

    protected function setUp(): void
    {
        $this->handler = new Handler(Mockery::mock(Container::class));
        $this->responseFactoryMock = Mockery::mock(ResponseFactory::class);
    }

    public function test_it_transforms_a_generic_exception_into_json(): void
    {
        $this
            ->responseFactoryMock
            ->allows('json')
            ->andReturnUsing(function ($response) {
                assertEquals([
                    'errors' => [
                        [
                            'status' => 500,
                            'detail' => 'Some internal error',
                        ],
                    ],
                ], $response);
            });

        $this->handler->setResponseFactory($this->responseFactoryMock);

        $requestMock = Mockery::mock(Request::class);

        $exception = new Exception('Some internal error', 500);

        $this->handler->render($requestMock, $exception);
    }

    public function test_it_transforms_a_request_exception_into_json_and_use_status_code(): void
    {
        $this
            ->responseFactoryMock
            ->allows('json')
            ->andReturnUsing(function ($response) {
                assertEquals([
                    'errors' => [
                        [
                            'status' => 400,
                            'detail' => 'Some request error',
                        ],
                    ],
                ], $response);
            });

        $this->handler->setResponseFactory($this->responseFactoryMock);

        $requestMock = Mockery::mock(Request::class);

        $exception = new BadRequestException('Some request error', 400);

        $this->handler->render($requestMock, $exception);
    }

    public function test_it_transforms_a_validation_exception_into_a_multi_error_exception(): void
    {
        $this
            ->responseFactoryMock
            ->allows('json')
            ->andReturnUsing(function ($response) {
                assertCount(2, $response['errors']);
            });
        $this->handler->setResponseFactory($this->responseFactoryMock);

        $requestMock = Mockery::mock(Request::class);

        $messageBag = Mockery::mock(MessageBag::class);
        $messageBag
            ->expects('get')
            ->with('some.missing.pointer')
            ->andReturns(['You are missing required input bro!']);
        $messageBag
            ->expects('get')
            ->with('some.invalid.pointer')
            ->andReturns(['Your input is invalid yo!']);

        $validator = Mockery::mock(Validator::class, [
            'failed' => [
                'some.missing.pointer' => [],
                'some.invalid.pointer' => [],
            ],
            'errors' => $messageBag,
        ]);

        $validationException = Mockery::mock(ValidationException::class);
        $validationException->validator = $validator;

        $this->handler->render($requestMock, $validationException);
    }
}
