<?php

namespace SoftC\Evotor\MobileCashier\Tests\Data;

use PHPUnit\Framework\TestCase;
use SoftC\Evotor\MobileCashier\Data\Receipt;
use SoftC\Evotor\MobileCashier\Data\Position;

final class ReceiptTest extends TestCase
{
    public function testConstructorWithDefaults(): void
    {
        $uuid = 'uuid';
        $positions = [

        ];

        $receipt = new Receipt($uuid, $positions);

        $this->assertEquals($uuid, $receipt->receipt_uuid);
        $this->assertEquals(true, $receipt->should_print_receipt);
        $this->assertCount(0, $receipt->positions);
    }

    public function testConstructor(): void
    {
        $uuid = 'uuid';
        $positions = [
            new Position('uuid', 'name', 'шт', 0, 123.45, 1),
        ];
        $should_print_receipt = false;

        $receipt = new Receipt($uuid, $positions, $should_print_receipt);

        $this->assertEquals($uuid, $receipt->receipt_uuid);
        $this->assertEquals($should_print_receipt, $receipt->should_print_receipt);
        $this->assertCount(1, $receipt->positions);
        $this->assertContains($positions[0], $receipt->positions);
    }
}