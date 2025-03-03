<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Exceptions\ExceptionRendering;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionRenderingTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_maps_validation_exception_correctly(): void
    {
        $handler = $this->mockExceptionHandler();

        $translator = Mockery::mock(Translator::class);
        $translator
            ->expects('get')
            ->with('The given data was invalid.')
            ->andReturn('The given data was invalid.');

        $validator = Mockery::mock(Validator::class);
        $validator->expects('failed')->andReturn(['path.to.problem' => 'Something']);
        $validator->expects('getTranslator')->andReturn($translator);

        $errors = Mockery::mock(MessageBag::class);
        $errors->expects('get')->andReturn(['Some Error', 'field is required']);
        $errors->expects('all')->andReturn([['Some Error', 'field is required']]);

        $validator->expects('errors')->twice()->andReturn($errors);

        $exception = new class ($validator) extends ValidationException {
            protected $code = 422;
        };

        $exception->validator = $validator;

        /** @var JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        // since status and message are final and cannot be changed, from their default values by mocking the class.
        $this->assertEquals([
            'errors' => [
                [
                    'status'  => 422,
                    'message' => 'The given data was invalid.',
                    'title'   => 'Invalid input',
                    'detail'  => 'Some Error',
                    'source'  => [
                        'pointer' => 'path/to/problem',
                    ],
                ],
                [
                    'status'  => 422,
                    'message' => 'The given data was invalid.',
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
        $handler = $this->mockExceptionHandler();
        $exception = new Exception('Some error', 300);

        /** @var JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        $this->assertEquals([
            'errors' => [
                [
                    'status' => 300,
                    'detail' => 'Some error',
                ],
            ],
        ], $response->getData(true));
    }

    public function test_it_maps_default_exception_with_debugging(): void
    {
        $handler = $this->mockExceptionHandler(true);
        $exception = new Exception('Some error', 300);

        /** @var JsonResponse $response */
        $response = $handler->render(new Request(), $exception);

        $error = $response->getData(true)['errors'][0];

        $this->assertEquals(300, $error['status']);
        $this->assertEquals('Some error', $error['detail']);
        $this->assertArrayHasKey('trace', $error);
    }

    public function test_it_maps_default_exception_status(): void
    {
        $handler = $this->mockExceptionHandler();

        // BadRequestException Implements `RequestExceptionInterface` which we handle
        $exception = new BadRequestException();
        $response = $handler->render(new Request(), $exception);
        $this->assertEquals(400, $response->getStatusCode());

        // HttpException implements `RequestExceptionInterface` which we handle
        $exception = new HttpException(301);
        $response = $handler->render(new Request(), $exception);
        $this->assertEquals(301, $response->getStatusCode());

        // If an unhandled exception is encountered, defaults to 500
        $exception = new Exception();
        $response = $handler->render(new Request(), $exception);
        $this->assertEquals(500, $response->getStatusCode());
    }

    private function mockExceptionHandler(bool $debug = false): Handler
    {
        $exceptionRendering = new ExceptionRendering($debug);
        $handler = new Handler(Mockery::mock(Container::class));
        $exceptions = new Exceptions($handler);
        $exceptionRendering($exceptions);

        return $handler;
    }

}
