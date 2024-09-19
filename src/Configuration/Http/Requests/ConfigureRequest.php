<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use MyParcelCom\Integration\Configuration\Properties\PropertyType;

class ConfigureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data.properties' => 'array',
            'data.properties.*.name' => 'required|string',
            'data.properties.*.type' => ['required', new Enum(PropertyType::class)],
            'data.properties.*.description' => 'string',
            'data.properties.*.enum' => 'array',
            'data.properties.*.meta' => 'array',
        ];
    }
}
