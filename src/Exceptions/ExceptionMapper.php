<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/*
| Maps exceptions to API error responses.
| It does this by being passed to `Application::configure()->withExceptions()` in `boostrap/app.php`
| Internally this class gets invoked and overwrite the default Laravel Exception HTTP responses.
*/

readonly class ExceptionMapper
{
    public function __construct(private bool $debug = false)
    {
    }

    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->dontReport([
            InvalidArgumentException::class,
        ]);
        $exceptions->render(function (ValidationException $e) {
            return response()->json(
                $this->getValidationExceptionBody($e),
                $e->status,
                $this->getExceptionHeaders()
            );
        });
        $exceptions->render(function (Throwable $e) {
            return response()->json(
                $this->getDefaultExceptionBody($e, $this->debug),
                $this->getDefaultExceptionStatus($e),
                $this->getExceptionHeaders()
            );
        });
    }

    public function getValidationExceptionBody(ValidationException $e): array
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

    private function getDefaultExceptionBody(Throwable $e, bool $debug): array
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

    private function getDefaultExceptionStatus(Throwable $e): int
    {
        if ($e instanceof RequestExceptionInterface) {
            return (int) $e->getCode();
        }

        if ($e instanceof HttpExceptionInterface) {
            return (int) $e->getStatusCode();
        }

        return 500;
    }

    private function getExceptionHeaders(): array
    {
        return [
            'Content-Type' => 'application/vnd.api+json',
        ];
    }

}
