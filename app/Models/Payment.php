<?php
namespace App\Models;

class Payment extends Model {
    protected string $table = 'payments';

    public function findByOrder(int $orderId): ?array {
        return $this->findBy('order_id', $orderId);
    }
}
