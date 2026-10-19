<?php
namespace App\Services;

use App\Models\Course;
use App\Models\Coupon;
use App\Helpers\Session;

class CartService {
    public static function getItems(): array {
        return Session::get('cart', []);
    }

    public static function add(int $courseId): bool {
        $cart = self::getItems();
        if (!in_array($courseId, $cart)) {
            $cart[] = $courseId;
            Session::set('cart', $cart);
            return true;
        }
        return false;
    }

    public static function remove(int $courseId): void {
        $cart = self::getItems();
        $cart = array_values(array_filter($cart, fn($id) => $id != $courseId));
        Session::set('cart', $cart);

        // If cart is empty, remove coupon
        if (empty($cart)) {
            self::removeCoupon();
        }
    }

    public static function clear(): void {
        Session::remove('cart');
        Session::remove('coupon');
    }

    public static function count(): int {
        return count(self::getItems());
    }

    public static function applyCoupon(string $code): array {
        $couponModel = new Coupon();
        $cartDetails = self::getDetails();
        $subtotal = $cartDetails['subtotal'];

        $coupon = $couponModel->findValidCoupon($code, $subtotal);
        if (!$coupon) {
            return ['success' => false, 'error' => 'កូដបញ្ចុះតម្លៃមិនត្រឹមត្រូវ ឬផុតកំណត់ ឬមិនទាន់ដល់ចំនួនកំណត់'];
        }

        Session::set('coupon', $coupon);
        return ['success' => true, 'coupon' => $coupon];
    }

    public static function removeCoupon(): void {
        Session::remove('coupon');
    }

    public static function getAppliedCoupon(): ?array {
        return Session::get('coupon');
    }

    public static function getDetails(): array {
        $itemIds = self::getItems();
        $courses = [];
        $subtotal = 0.0;

        if (!empty($itemIds)) {
            $courseModel = new Course();
            foreach ($itemIds as $id) {
                $c = $courseModel->find((int)$id);
                if ($c && $c['status'] === 'published') {
                    $itemPrice = $c['discount_price'] !== null ? (float)$c['discount_price'] : (float)$c['price'];
                    $c['effective_price'] = $itemPrice;
                    $courses[] = $c;
                    $subtotal += $itemPrice;
                }
            }
        }

        $discount = 0.0;
        $coupon = self::getAppliedCoupon();
        if ($coupon) {
            $couponModel = new Coupon();
            $discount = $couponModel->calculateDiscount($coupon, $subtotal);
        }

        $total = max(0.0, $subtotal - $discount);

        return [
            'items'       => $courses,
            'count'       => count($courses),
            'subtotal'    => $subtotal,
            'discount'    => $discount,
            'coupon'      => $coupon,
            'total'       => $total
        ];
    }
}
