<?php

namespace SoftC\Evotor\MobileCashier\Data;

/**
 * Description of Receipt
 *
 * @author capcom
 */
class Receipt {
    public $cashier_uuid;
    public $client_email;
    public $client_phone;
    public $creation_date;
    public $extra;
    
    public $payment_type;
    public $receipt_uuid;
    public $should_print_receipt;
    public $positions = [];
    
    public function __construct(string $uuid, array $positions, bool $should_print_receipt = true) {
        $this->receipt_uuid = $uuid;
        $this->positions = $positions;
        $this->should_print_receipt = $should_print_receipt;
    }
}
