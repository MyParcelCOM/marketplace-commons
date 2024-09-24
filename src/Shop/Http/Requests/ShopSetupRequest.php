<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopSetupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data.settings'     => 'array',
            'data.redirect_url' => 'url',
        ];
    }

    public function redirectUrl(): ?string
    {
        return $this->input('data.redirect_url');
    }
}
