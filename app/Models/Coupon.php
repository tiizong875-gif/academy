<?php
namespace App\Models;

class Coupon extends Model {
    protected string $table = 'coupons';

    public function findValidCoupon(string $code, float $orderAmount = 0.0): ?array {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM `{$this->table}` 
                WHERE code = :code 
                  AND is_active = 1
                  AND (start_date IS NULL OR start_date <= :today1)
                  AND (expiry_date IS NULL OR expiry_date >= :today2)
                  AND (usage_limit = 0 OR usage_count < usage_limit)
                  AND min_order_amount <= :amount
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'code'   => strtoupper(trim($code)),
            'today1' => $today,
            'today2' => $today,
            'amount' => $orderAmount
        ]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function calculateDiscount(array $coupon, float $subtotal): float {
        if ($coupon['discount_type'] === 'percentage') {
            $discount = ($subtotal * (float)$coupon['discount_value']) / 100;
        } else {
            $discount = (float)$coupon['discount_value'];
        }

        if (!empty($coupon['max_discount_amount']) && (float)$coupon['max_discount_amount'] > 0) {
            $discount = min($discount, (float)$coupon['max_discount_amount']);
        }

        return min($discount, $subtotal);
    }
}
