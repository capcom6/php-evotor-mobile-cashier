<?php

namespace SoftC\Evotor\MobileCashier\Tests\Data;

use PHPUnit\Framework\TestCase;
use SoftC\Evotor\MobileCashier\Data\Position;

final class PositionTest extends TestCase {
    public function testConstructor(): void {
        $uuid = 'uuid';
        $name = 'name';
        $measureName = 'кг';
        $precision = 3;
        $price = '123.45';
        $quantity = '1.234';

        $position = new Position($uuid, $name, $measureName, $precision, $price, $quantity);

        $this->assertEquals($uuid, $position->position_uuid);
        $this->assertEquals($name, $position->name);
        $this->assertEquals($measureName, $position->measureName);
        $this->assertEquals($precision, $position->precision);
        $this->assertEquals($price, $position->price);
        $this->assertEquals($quantity, $position->quantity);
    }
}