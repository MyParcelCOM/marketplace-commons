<?php

declare(strict_types=1);

namespace Tests\Shipment;

use MyParcelCom\Integration\Shipment\PhysicalProperties;
use PHPUnit\Framework\TestCase;
use function random_int;

class PhysicalPropertiesTest extends TestCase
{
    public function test_it_should_convert_physical_properties_to_array_with_weight_only(): void
    {
        $weight = random_int(1000, 9000);

        $physicalProperties = new PhysicalProperties($weight);

        self::assertEquals([
            'weight' => $weight,
        ], $physicalProperties->toArray());
    }

    public function test_it_should_convert_physical_properties_to_array_with_all_inputs(): void
    {
        $weight = random_int(1000, 9000);
        $width = random_int(100, 200);
        $height = random_int(200, 300);
        $length = random_int(400, 500);
        $volume = random_int(600, 700);

        $physicalProperties = new PhysicalProperties(
            weight: $weight,
            height: $height,
            width: $width,
            length: $length,
            volume: $volume,
        );

        self::assertEquals([
            'weight' => $weight,
            'height' => $height,
            'width'  => $width,
            'length' => $length,
            'volume' => $volume,
        ], $physicalProperties->toArray());
    }
}
