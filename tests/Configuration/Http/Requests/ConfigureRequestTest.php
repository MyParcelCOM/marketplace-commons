<?php

declare(strict_types=1);

namespace Configuration\Http\Requests;

use MyParcelCom\Integration\Configuration\Http\Requests\ConfigureRequest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;

class ConfigureRequestTest extends TestCase
{
    public function test_it_gets_property_value_from_request(): void
    {
        $request = new ConfigureRequest();
        $request->replace([
            'data' => [
                'my_name'  => 'my_value',
                'my_group' => [
                    'my_sub_name' => 'my_sub_value',
                ],
            ],
        ]);
        assertNull($request->getPropertyValue('no.such_key'));
        assertEquals('my_value', $request->getPropertyValue('my_name'));
        assertEquals('my_sub_value', $request->getPropertyValue('my_group.my_sub_name'));
    }
}
