<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Exceptions\ExceptionRendering;
use PHPUnit\Framework\TestCase;

class ExceptionRenderingTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    public function test_it_maps_validation_exception_correctly(): void
    {
        $exceptionRendering = new ExceptionRendering();

        $validator = Mockery::mock(Validator::class);
        $validator->expects('failed')->andReturn(['path.to.problem' => 'Something']);

        $errors = Mockery::mock(MessageBag::class);
        $errors->expects('get')->andReturn(['Some Error', 'field is required']);

        $validator->expects('errors')->andReturn($errors);

        $exception = Mockery::mock(ValidationException::class);

        $exception->validator = $validator;

        $handler = new Handler(Mockery::mock(Container::class));
        $exceptions = new Exceptions($handler);
        $exceptionRendering($exceptions);

        /** @var \Illuminate\Http\JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        // since status and message are final and cannot be changed, from their default values by mocking the class.
        $this->assertEquals([
            'errors' => [
                [
                    'status'  => 0,
                    'message' => '',
                    'title'   => 'Invalid input',
                    'detail'  => 'Some Error',
                    'source'  => [
                        'pointer' => 'path/to/problem',
                    ],
                ],
                [
                    'status'  => 0,
                    'message' => '',
                    'title'   => 'Missing input',
                    'detail'  => 'field is required',
                    'source'  => [
                        'pointer' => 'path/to/problem',
                    ],
                ],
            ],
        ], $response->getData(true));
    }

    public function test_it_maps_default_exception_without_debugging(): void
    {
        $exceptionRendering = new ExceptionRendering();

        $exception = new Exception('Some error',300);

        $handler = new Handler(Mockery::mock(Container::class));
        $exceptions = new Exceptions($handler);
        $exceptionRendering($exceptions);

        /** @var \Illuminate\Http\JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        $this->assertEquals([
            'errors' => [
                [
                    'status'  => 300,
                    'detail'  => 'Some error',
                ],
            ],
        ], $response->getData(true));
    }

    public function test_it_maps_default_exception_with_debugging(): void
    {
        $exceptionRendering = new ExceptionRendering(true);

        $exception = new Exception('Some error',300);

        $handler = new Handler(Mockery::mock(Container::class));
        $exceptions = new Exceptions($handler);
        $exceptionRendering($exceptions);

        /** @var \Illuminate\Http\JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        $error = $response->getData(true)['errors'][0];

        $this->assertEquals(300, $error['status']);
        $this->assertEquals('Some error', $error['detail']);
        $this->assertArrayHasKey('trace', $error);
    }
}
