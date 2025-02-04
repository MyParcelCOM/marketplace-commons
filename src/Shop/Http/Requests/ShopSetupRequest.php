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
            'data.organization_id'  => 'required|string|uuid',
            'data.broker_id'        => 'required|string|uuid',
            // Optional, but if exists, it must be an array with 2 elements.
            // This makes sure that an empty array wouldn't` pass the validation.
            'data.mp_client'        => 'array|min:2|max:2',
            // Required if `mp_client` exists
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

    public function organizationId(): string
    {
        return $this->input('data.organization_id');
    }

    public function brokerId(): string
    {
        return $this->input('data.broker_id');
    }
}
