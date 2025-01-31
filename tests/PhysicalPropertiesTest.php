<?php

declare(strict_types=1);

namespace Tests;

use MyParcelCom\Integration\PhysicalProperties;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class PhysicalPropertiesTest extends TestCase
{
    /**
     * @throws \Random\RandomException
     */
    public function test_it_should_convert_physical_properties_to_array_with_weight_only(): void
    {
        $weight = random_int(1000, 9000);

        $physicalProperties = new PhysicalProperties($weight);

        assertEquals([
            'weight' => $weight,
        ], $physicalProperties->toArray());
    }

    /**
     * @throws \Random\RandomException
     */
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

        assertEquals([
            'weight' => $weight,
            'height' => $height,
            'width'  => $width,
            'length' => $length,
            'volume' => $volume,
        ], $physicalProperties->toArray());
    }
}
