<?php

namespace Tests\Unit;

use App\Exceptions\DiveException;
use App\Library\DiveCalculator;
use PHPUnit\Framework\TestCase;

class DiveCalculatorTest extends TestCase
{
    public function test_it_calculates_pressure_group(): void
    {
        $dive_calculator = new DiveCalculator();
        $pressure_group = $dive_calculator->getPressureGroup(60, 45);
        $this->assertEquals('S', $pressure_group);

        $pressure_group = $dive_calculator->getPressureGroup(30, 90);
        $this->assertEquals('Q', $pressure_group);
    }

    public function test_it_calculates_pressure_group_with_residual_time(): void
    {
        $dive_calculator = new DiveCalculator();
        $pressure_group = $dive_calculator->getPressureGroup(60, 25, 20);
        $this->assertEquals('S', $pressure_group);

        $pressure_group = $dive_calculator->getPressureGroup(30, 70, 20);
        $this->assertEquals('Q', $pressure_group);

        $this->expectException(DiveException::class);
        $this->expectExceptionMessage(DiveCalculator::MESSAGE_DEPTH_OUTSIDE_RECREATIONAL_LIMIT);
        $dive_calculator->getPressureGroup(150, 25, 20);

        $this->expectException(DiveException::class);
        $this->expectExceptionMessage(DiveCalculator::MESSAGE_TIME_OUTSIDE_RECREATIONAL_LIMIT);
        $dive_calculator->getPressureGroup(60, 90);
    }

    public function test_it_calculates_new_pressure_group_after_interval(): void
    {
        $dive_calculator = new DiveCalculator();
        $new_pressure_group = $dive_calculator->getNewPressureGroup('W', 60);

        $this->assertEquals('I', $new_pressure_group);

        $null_pressure_group = $dive_calculator->getNewPressureGroup('W', 500);
        $this->assertNull($null_pressure_group);

        $this->expectException(DiveException::class);
        $this->expectExceptionMessage(DiveCalculator::MESSAGE_INVALID_PRESSURE_GROUP);
        $dive_calculator->getNewPressureGroup(60, 90);
    }

    public function test_it_calculates_max_bottom_time(): void
    {
        $dive_calculator = new DiveCalculator();
        $max_bottom_time = $dive_calculator->getMaxBottomTime(60);

        $this->assertEquals(55, $max_bottom_time);
    }
}
