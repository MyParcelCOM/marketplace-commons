<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Configuration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function getPropertyValue(string $propertyName): mixed
    {
        return $this->input('data', [])[$propertyName] ?? null;
    }
}
