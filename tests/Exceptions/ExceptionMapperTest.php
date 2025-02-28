<?php

declare(strict_types=1);

namespace Tests\Exceptions;

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
    public function test_it_maps_validation_exception_correctly(): void
    {
        $exceptionMapper = new ExceptionMapper();

        $validator = Mockery::mock(Validator::class);
        $validator->expects('failed')->andReturn(['path.to.problem' => 'Something']);

        $errors = Mockery::mock(MessageBag::class);
        $errors->expects('get')->andReturn(['Some Error', 'field is required']);

        $validator->expects('errors')->andReturn($errors);

        $exception = Mockery::mock(ValidationException::class);

        $exception->validator = $validator;

        $response = $exceptionMapper->getValidationExceptionBody($exception);

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
        ], $response);
    }
}
