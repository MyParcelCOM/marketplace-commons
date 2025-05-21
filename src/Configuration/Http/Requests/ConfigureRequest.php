<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ConfigureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data' => 'required|array',
        ];
    }

    /**
     * Get a property value from the request using "dot" notation.
     */
    public function getPropertyValue(string $propertyName, mixed $default = null): mixed
    {
        return Arr::get($this->input('data', []), $propertyName, $default);
    }
}
