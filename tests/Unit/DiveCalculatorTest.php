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

    public function test_it_calculates_pressure_group_with_surface_interval(): void
    {
        $dive_calculator = new DiveCalculator();
        $pressure_group = $dive_calculator->getPressureGroup(60, 25, 20);
        $this->assertEquals('S', $pressure_group);

        $pressure_group = $dive_calculator->getPressureGroup(30, 70, 20);
        $this->assertEquals('Q', $pressure_group);
    }

    public function test_it_throws_exception_over_depth(): void
    {
        $this->expectException(DiveException::class);
        $this->expectExceptionMessage(DiveCalculator::MESSAGE_DEPTH_OUTSIDE_RECREATIONAL_LIMIT);

        $dive_calculator = new DiveCalculator();
        $dive_calculator->getPressureGroup(150, 25, 20);
    }

    public function test_it_throws_exception_over_time(): void
    {
        $this->expectException(DiveException::class);
        $this->expectExceptionMessage(DiveCalculator::MESSAGE_TIME_OUTSIDE_RECREATIONAL_LIMIT);

        $dive_calculator = new DiveCalculator();
        $dive_calculator->getPressureGroup(60, 90);
    }
}
