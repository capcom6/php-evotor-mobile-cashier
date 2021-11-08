<?php

namespace SoftC\Evotor\MobileCashier\Data;

/**
 * Description of Position
 *
 * @author capcom
 */
class Position {
    public $alcohol_by_volume;
    public $alcohol_product_kind_code;
    public $commodity_id;
    public $mark;
    public $tare_volume;
    public $tax;
    public $type;
    
    
    public $measureName;
    public $name;
    public $position_uuid;
    public $precision;
    public $price;
    public $quantity;


    public function __construct(
            string $uuid, 
            string $name, 
            string $measureName, 
            int $precision,
            string $price,
            string $quantity) {
        $this->position_uuid = $uuid;
        $this->name = $name;
        $this->measureName = $measureName;
        $this->precision = $precision;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
