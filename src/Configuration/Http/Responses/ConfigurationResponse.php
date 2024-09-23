<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use MyParcelCom\Integration\Configuration\Form\Form;

class ConfigurationResponse implements Responsable
{
    public function __construct(private readonly Form $form)
    {
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse([
            'configuration_schema' =>
                [
                    '$schema'              => 'http://json-schema.org/draft-04/schema#',
                    'additionalProperties' => false,
                    'required'             => $this->form->getRequired(),
                    'properties'           => Arr::collapse($this->form->toArray()),
                ],
        ]);
    }
}
