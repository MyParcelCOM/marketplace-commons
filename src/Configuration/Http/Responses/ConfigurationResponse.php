<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use MyParcelCom\JsonSchema\FormBuilder\Form\Form;

class ConfigurationResponse implements Responsable
{
    public function __construct(
        private readonly Form $form,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            array_filter([
                'configuration_schema' => $this->form->toJsonSchema(),
                'values'               => $this->form->getValues(),
            ]),
        );
    }
}
