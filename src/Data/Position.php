<?php

namespace SoftC\Evotor\MobileCashier\Data;

/**
 * Description of Position
 *
 * @author capcom
 */
class Position {
    public ?float $alcohol_by_volume;
    public ?int $alcohol_product_kind_code;
    public ?string $commodity_id;
    public ?string $mark;
    public ?float $tare_volume;
    public ?string $tax;
    public string $type;


    public string $measureName;
    public string $name;
    public string $position_uuid;
    public int $precision;
    public string $price;
    public string $quantity;


    public function __construct(
        string $uuid,
        string $name,
        string $measureName,
        int $precision,
        string $price,
        string $quantity
    ) {
        $this->position_uuid = $uuid;
        $this->name = $name;
        $this->measureName = $measureName;
        $this->precision = $precision;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}