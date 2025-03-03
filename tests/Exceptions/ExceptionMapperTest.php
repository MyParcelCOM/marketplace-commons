<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use MyParcelCom\Integration\Exceptions\ExceptionMapper;
use PHPUnit\Framework\TestCase;

class ExceptionMapperTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private ExceptionMapper $exceptionMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exceptionMapper = new ExceptionMapper();
    }

    public function test_it_maps_validation_exception_correctly(): void
    {
        $validator = Mockery::mock(Validator::class);
        $validator->expects('failed')->andReturn(['path.to.problem' => 'Something']);

        $errors = Mockery::mock(MessageBag::class);
        $errors->expects('get')->andReturn(['Some Error', 'field is required']);

        $validator->expects('errors')->andReturn($errors);

        $exception = Mockery::mock(ValidationException::class);

        $exception->validator = $validator;

        $response = $this->exceptionMapper->getValidationExceptionBody($exception);

        // In `ValidationException`, `status` and `message` are final  and cannot be mocked.
        // For this reason, we compare the default values instead (extracted with `getCode` and `getMessage` respectively).
        $this->assertEquals([
            'errors' => [
                [
                    'status'  => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'title'   => 'Invalid input',
                    'detail'  => 'Some Error',
                    'source'  => [
                        'pointer' => 'path/to/problem',
                    ],
                ],
                [
                    'status'  => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'title'   => 'Missing input',
                    'detail'  => 'field is required',
                    'source'  => [
                        'pointer' => 'path/to/problem',
                    ],
                ],
            ],
        ], $response);
    }

    public function test_it_maps_default_exception_correctly(): void
    {
        $exception = new Exception('Some error',300);

        $response = $this->exceptionMapper->getDefaultExceptionBody($exception, false);

        $this->assertEquals([
            'errors' => [
                [
                    'status'  => 300,
                    'detail'  => 'Some error',
                ],
            ],
        ], $response);

        $response = $this->exceptionMapper->getDefaultExceptionBody($exception, true);
        $error = $response['errors'][0];

        $this->assertEquals(300, $error['status']);
        $this->assertEquals('Some error', $error['detail']);
        $this->assertArrayHasKey('trace', $error);
    }
}
