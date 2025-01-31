<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopSetupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'data.settings'         => 'array',
            'data.redirect_url'     => 'url',
            'data.mp_client'        => 'array',
            'data.mp_client.id'     => 'required_with:data.mp_client|string|uuid',
            'data.mp_client.secret' => 'required_with:data.mp_client|string',
        ];
    }

    public function redirectUrl(): ?string
    {
        return $this->input('data.redirect_url');
    }

    public function mpClientId(): ?string
    {
        return $this->input('data.mp_client.id');
    }

    public function mpClientSecret(): ?string
    {
        return $this->input('data.mp_client.secret');
    }
}
