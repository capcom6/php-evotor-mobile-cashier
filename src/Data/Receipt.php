<?php

namespace SoftC\Evotor\MobileCashier\Data;

use DateTime;
use SoftC\Evotor\MobileCashier\Data\Position;

/**
 * Description of Receipt
 *
 * @author capcom
 */
class Receipt {
    public string $receipt_uuid;
    public ?string $cashier_uuid;
    public ?string $client_email;
    public ?string $client_phone;
    public ?DateTime $creation_date;
    /**
     * Дополнительные поля для отображения
     * @var array<string, string>
     */
    public ?array $extra;

    public ?string $payment_type;
    public ?bool $should_print_receipt;
    /**
     * Список позиций заказа
     * @var Position[]
     */
    public array $positions = [];

    /**
     * Конструктор нового заказа
     * @param string $uuid уникальный идентификатор
     * @param Position[] $positions список позиций
     * @param bool $should_print_receipt необходимость печати бумажной копии
     */
    public function __construct(string $uuid, array $positions, bool $should_print_receipt = true) {
        $this->receipt_uuid = $uuid;
        $this->positions = $positions;
        $this->should_print_receipt = $should_print_receipt;
    }
}