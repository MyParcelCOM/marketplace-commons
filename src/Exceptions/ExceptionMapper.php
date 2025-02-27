<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * This class is used to map exceptions to API error responses.
 */

readonly class ExceptionMapper
{

    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->dontReport([
            InvalidArgumentException::class,
        ]);
        $exceptions->render(function (ValidationException $e) {
            return response()->json(
                self::getValidationExceptionBody($e),
                $e->status,
                self::getExceptionHeaders()
            );
        });
        $exceptions->render(function (Throwable $e) {
            return response()->json(
                self::getDefaultExceptionBody($e, config('app.debug', false)),
                self::getDefaultExceptionStatus($e),
                self::getExceptionHeaders()
            );
        });
    }

    public static function getValidationExceptionBody(ValidationException $e): array
    {
        $validator = $e->validator;
        $invalidAttributes = array_keys($validator->failed());
        $errors = [];

        foreach ($invalidAttributes as $invalidAttribute) {
            $errorMessages = $validator->errors()->get($invalidAttribute);

            foreach ($errorMessages as $errorMessage) {
                $pointer = str_replace('.', '/', $invalidAttribute);
                $title = str_contains($errorMessage, 'required') ? 'Missing input' : 'Invalid input';
                $errors[] = [
                    'status'  => $e->getCode(),
                    'message' => $e->getMessage(),
                    'title'   => $title,
                    'detail'  => $errorMessage,
                    'source'  => ['pointer' => $pointer],
                ];
            }
        }
        return [
            'errors' => $errors,
        ];
    }

    public static function getDefaultExceptionBody(Throwable $e, bool $debug): array
    {
        $error = [
            'status' => $e->getCode(),
            'detail' => $e->getMessage(),
        ];
        if ($debug) {
            $error['trace'] = $e->getTrace();
        }

        return [
            'errors' => [
                $error,
            ],
        ];
    }

    public static function getDefaultExceptionStatus(Throwable $e): int
    {
        if ($e instanceof RequestExceptionInterface) {
            return (int) $e->getCode();
        }

        if ($e instanceof HttpExceptionInterface) {
            return (int) $e->getStatusCode();
        }

        return 500;
    }

    public static function getExceptionHeaders(): array
    {
        return [
            'Content-Type' => 'application/vnd.api+json',
        ];
    }

}
