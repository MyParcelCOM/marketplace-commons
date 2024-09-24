<?php

declare(strict_types=1);

namespace Tests\Shop;

use ArgumentCountError;
use MyParcelCom\Integration\Shop\ShopId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ShopIdTest extends TestCase
{
    public function test_should_fail_creating_shop_id_value_object_because_of_no_uuid(): void
    {
        $this->expectException(ArgumentCountError::class);
        /** @noinspection PhpParamsInspection */
        /** @noinspection PhpExpressionResultUnusedInspection */
        new ShopId();
    }

    public function test_should_create_shop_id_value_object_generating_uuid(): void
    {
        self::assertInstanceOf(ShopId::class, new ShopId(Uuid::uuid4()));
    }

    public function test_should_convert_shop_id_value_object_to_uuid_string(): void
    {
        self::assertIsString((string) new ShopId(Uuid::uuid4()));
        self::assertIsString((new ShopId(Uuid::uuid4()))->toString());
    }
}
