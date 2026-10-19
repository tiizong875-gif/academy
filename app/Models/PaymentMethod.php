<?php
namespace App\Models;

class PaymentMethod extends Model {
    protected string $table = 'payment_methods';

    public function getActive(): array {
        return $this->where('is_active', 1, 'sort_order ASC, id ASC');
    }
}
