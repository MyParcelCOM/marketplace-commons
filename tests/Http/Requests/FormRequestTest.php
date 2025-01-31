<?php

declare(strict_types=1);

namespace Tests\Http\Requests;

use MyParcelCom\Integration\Http\Requests\FormRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class FormRequestTest extends TestCase
{
    public function testShopId(): void
    {
        $request = new FormRequest();
        $request->initialize(['shop_id' => '123e4567-e89b-12d3-a456-426614174000']);

        assertEquals('123e4567-e89b-12d3-a456-426614174000', $request->shopId()->toString());
    }

    public function testShopIdThrowsExceptionWhenNoShopIdProvided(): void
    {
        $request = new FormRequest();

        $this->expectExceptionMessage('No shop_id provided in the request query');
        $request->shopId();
    }

    public function testShopIdThrowsExceptionWhenShopIdIsNotValidUuid(): void
    {
        $request = new FormRequest();
        $request->initialize(['shop_id' => 'invalid-uuid']);

        $this->expectExceptionMessage('shop_id is not a valid UUID');
        $request->shopId();
    }
}
