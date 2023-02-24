<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Exceptions;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [];

    protected $dontReport = [
        InvalidArgumentException::class,
    ];

    private ResponseFactory $responseFactory;

    private bool $debug = false;

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    public function setResponseFactory(ResponseFactory $responseFactory): self
    {
        $this->responseFactory = $responseFactory;

        return $this;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        }

        if ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        if ($e instanceof ValidationException) {
            return $this->responseFactory->json(
                [
                    'errors' => $this->mapValidationException($e),
                ],
                $e->getCode(),
                [
                    'Content-Type' => 'application/vnd.api+json',
                ]
            );
        }

        $error = [
            'status' => $e->getCode(),
            'detail' => $e->getMessage(),
        ];

        if ($this->debug) {
            $error['trace'] = $e->getTrace();
        }

        $status = 500;

        if ($e instanceof RequestExceptionInterface) {
            $status = (int) $e->getCode();
        }

        return $this->responseFactory->json(
            [
                'errors' => [
                    $error,
                ],
            ],
            $status,
            [
                'Content-Type' => 'application/vnd.api+json',
            ]
        );
    }

    private function mapValidationException(ValidationException $exception): array
    {
        $validator = $exception->validator;
        $invalidAttributes = array_keys($validator->failed());
        $errors = [];

        foreach ($invalidAttributes as $invalidAttribute) {
            $errorMessages = $validator->errors()->get($invalidAttribute);

            foreach ($errorMessages as $errorMessage) {
                $pointer = str_replace('.', '/', $invalidAttribute);
                $title = str_contains($errorMessage, 'required') ? 'Missing input' : 'Invalid input';
                $errors[] = [
                    'status'  => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'title'   => $title,
                    'detail'  => $errorMessage,
                    'source'  => ['pointer' => $pointer],
                ];
            }
        }

        return $errors;
    }
}
