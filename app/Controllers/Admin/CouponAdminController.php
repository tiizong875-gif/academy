<?php
namespace App\Controllers\Admin;

use App\Models\Coupon;

class CouponAdminController extends AdminController {
    public function index(): void {
        $couponModel = new Coupon();
        $coupons = $couponModel->all('id DESC');

        $this->renderAdmin('admin/coupons/index', [
            'coupons' => $coupons,
            'title'   => 'គ្រប់គ្រងប័ណ្ណបញ្ចុះតម្លៃ (Coupons & Discounts)'
        ]);
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $code = strtoupper(trim((string)$this->request->input('code', '')));
        $discountType = $this->request->input('discount_type', 'percentage');
        $discountValue = (float)$this->request->input('discount_value', 0);
        $minOrder = (float)$this->request->input('min_order_amount', 0);
        $maxDiscount = $this->request->input('max_discount_amount') !== '' ? (float)$this->request->input('max_discount_amount') : null;
        $usageLimit = (int)$this->request->input('usage_limit', 100);
        $startDate = $this->request->input('start_date') ?: null;
        $expiryDate = $this->request->input('expiry_date') ?: null;

        if (empty($code) || $discountValue <= 0) {
            $this->withError('សូមបំពេញកូដបញ្ចុះតម្លៃ និងតម្លៃបញ្ចុះឱ្យបានត្រឹមត្រូវ');
            $this->redirectBack();
            return;
        }

        $couponModel = new Coupon();
        if ($couponModel->findBy('code', $code)) {
            $this->withError('កូដនេះមានរួចរាល់ហើយ សូមជ្រើសរើសកូដផ្សេង');
            $this->redirectBack();
            return;
        }

        $id = $couponModel->create([
            'code'                => $code,
            'discount_type'       => $discountType,
            'discount_value'      => $discountValue,
            'min_order_amount'    => $minOrder,
            'max_discount_amount' => $maxDiscount,
            'usage_limit'         => $usageLimit,
            'start_date'          => $startDate,
            'expiry_date'         => $expiryDate,
            'is_active'           => 1
        ]);

        $this->logAction('CREATE_COUPON', 'Coupon', $id, "បានបង្កើតកូដបញ្ចុះតម្លៃ៖ {$code}");
        $this->withSuccess('បានបង្កើតប័ណ្ណបញ្ចុះតម្លៃដោយជោគជ័យ!');
        $this->redirect('/admin/coupons');
    }

    public function delete(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $couponModel = new Coupon();
        $coupon = $couponModel->find((int)$id);
        if ($coupon) {
            $couponModel->delete((int)$id);
            $this->logAction('DELETE_COUPON', 'Coupon', (int)$id, "បានលុបកូដបញ្ចុះតម្លៃ៖ {$coupon['code']}");
            $this->withSuccess('បានលុបកូដបញ្ចុះតម្លៃដោយជោគជ័យ!');
        }

        $this->redirect('/admin/coupons');
    }
}
