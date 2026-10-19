<?php
namespace App\Controllers;

use App\Services\CartService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Enrollment;
use App\Models\Coupon;
use App\Helpers\Auth;
use App\Helpers\Upload;

class OrderController extends Controller
{
    /**
     * Ensure the current user is logged in.
     */
    private function requireLogin(): bool
    {
        if (!Auth::id()) {
            $this->withWarning('សូម Login ជាមុនសិន។');
            $this->redirect('/login');
            return false;
        }

        return true;
    }

    /**
     * Check whether a payment method is currently active.
     */
    private function getActivePaymentMethod(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $model = new PaymentMethod();

        foreach ($model->getActive() as $method) {
            if ((int) $method['id'] === $id) {
                return $method;
            }
        }

        return null;
    }

    /**
     * Display checkout page.
     */
    public function checkout(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        $cart = CartService::getDetails();

        if (empty($cart['count'])) {
            $this->withWarning(
                'កន្ត្រកទំនិញរបស់អ្នកទទេ។ សូមជ្រើសរើសវគ្គសិក្សាជាមុនសិន។'
            );
            $this->redirect('/courses');
            return;
        }

        $paymentMethodModel = new PaymentMethod();

        $this->render('cart/checkout', [
            'cart' => $cart,
            'paymentMethods' => $paymentMethodModel->getActive(),
            'title' => 'ការទូទាត់ប្រាក់ — ក្តឹបកូដ Academy',
        ]);
    }

    /**
     * Create order and order items.
     */
    public function processCheckout(): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        if (!$this->validateCsrf()) {
            return;
        }

        $cart = CartService::getDetails();

        if (
            empty($cart['count']) ||
            empty($cart['items']) ||
            !is_array($cart['items'])
        ) {
            $this->withError('កន្ត្រកទំនិញរបស់អ្នកទទេ ឬមិនត្រឹមត្រូវ។');
            $this->redirect('/courses');
            return;
        }

        // Never trust prices or totals submitted by the browser.
        // CartService must calculate prices on the server.
        $total = round((float) ($cart['total'] ?? -1), 2);

        if (!is_finite($total) || $total < 0) {
            $this->withError('តម្លៃការបញ្ជាទិញមិនត្រឹមត្រូវ។');
            $this->redirect('/checkout');
            return;
        }

        $userId = (int) Auth::id();

        $paymentMethodId = (int) $this->request->input(
            'payment_method_id',
            0
        );

        $notes = trim(
            (string) $this->request->input('notes', '')
        );

        if (mb_strlen($notes) > 1000) {
            $this->withError('ចំណាំមិនអាចលើសពី 1000 តួអក្សរបានទេ។');
            $this->redirect('/checkout');
            return;
        }

        $paymentMethod = null;

        if ($total > 0) {
            $paymentMethod = $this->getActivePaymentMethod(
                $paymentMethodId
            );

            if (!$paymentMethod) {
                $this->withError(
                    'សូមជ្រើសរើសវិធីសាស្ត្រទូទាត់ដែលអាចប្រើបាន។'
                );
                $this->redirect('/checkout');
                return;
            }
        }

        $orderModel = new Order();
        $db = $orderModel->getDb();

        try {
            $db->beginTransaction();

            $orderNumber = $orderModel->generateOrderNumber();

            $coupon = $cart['coupon'] ?? null;
            $couponCode = $coupon['code'] ?? null;

            // A free order can be completed immediately.
            // A paid order must remain pending until verified.
            $paymentStatus = ($total == 0.0)
                ? 'paid'
                : 'pending';

            $orderId = $orderModel->create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'subtotal' => round(
                    (float) ($cart['subtotal'] ?? 0),
                    2
                ),
                'discount_amount' => round(
                    (float) ($cart['discount'] ?? 0),
                    2
                ),
                'coupon_code' => $couponCode,
                'total_amount' => $total,
                'payment_method_id' => $paymentMethod
                    ? (int) $paymentMethod['id']
                    : null,
                'payment_status' => $paymentStatus,
                'notes' => $notes !== '' ? $notes : null,
            ]);

            if (!$orderId) {
                throw new \RuntimeException(
                    'Order creation failed.'
                );
            }

            $orderItemModel = new OrderItem();
            $enrollmentModel = new Enrollment();

            foreach ($cart['items'] as $item) {
                if (
                    !isset($item['id']) ||
                    !is_numeric($item['id']) ||
                    (int) $item['id'] <= 0
                ) {
                    throw new \RuntimeException(
                        'Invalid course in cart.'
                    );
                }

                $courseId = (int) $item['id'];
                $price = round(
                    (float) ($item['effective_price'] ?? -1),
                    2
                );

                if (!is_finite($price) || $price < 0) {
                    throw new \RuntimeException(
                        'Invalid course price.'
                    );
                }

                $itemId = $orderItemModel->create([
                    'order_id' => $orderId,
                    'course_id' => $courseId,
                    'price' => $price,
                ]);

                if (!$itemId) {
                    throw new \RuntimeException(
                        'Order item creation failed.'
                    );
                }

                // Enroll immediately only for a free order.
                if ($paymentStatus === 'paid') {
                    $enrollmentModel->enrollUser(
                        $userId,
                        $courseId,
                        $orderId
                    );
                }
            }

            // Record coupon usage within the same transaction.
            if ($coupon && !empty($coupon['id'])) {
                $couponId = (int) $coupon['id'];

                $stmt = $db->prepare(
                    'UPDATE coupons
                     SET usage_count = usage_count + 1
                     WHERE id = :id'
                );

                $stmt->execute(['id' => $couponId]);

                if ($stmt->rowCount() !== 1) {
                    throw new \RuntimeException(
                        'Coupon update failed.'
                    );
                }

                $stmt = $db->prepare(
                    'INSERT INTO coupon_usages
                     (coupon_id, user_id, order_id, discount_amount)
                     VALUES (:cid, :uid, :oid, :disc)'
                );

                $stmt->execute([
                    'cid' => $couponId,
                    'uid' => $userId,
                    'oid' => $orderId,
                    'disc' => round(
                        (float) ($cart['discount'] ?? 0),
                        2
                    ),
                ]);
            }

            $db->commit();

            // Clear the cart only after successful commit.
            CartService::clear();

            if ($paymentStatus === 'paid') {
                $this->withSuccess(
                    'ការបញ្ជាទិញបានជោគជ័យ! អ្នកអាចចាប់ផ្ដើមរៀនឥឡូវនេះ។'
                );

                $this->redirect('/student/my-courses');
                return;
            }

            $this->withSuccess(
                'ការបញ្ជាទិញបានបង្កើតរួចរាល់។ សូមបង់ប្រាក់ និង Upload បង្កាន់ដៃ។'
            );

            $this->redirect('/order/' . (int) $orderId);
            return;

        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            // Keep detailed errors in the server log only.
            error_log(
                '[OrderController::processCheckout] ' .
                $e->getMessage()
            );

            $this->withError(
                'មិនអាចបង្កើតការបញ្ជាទិញបានទេ។ សូមព្យាយាមម្ដងទៀត។'
            );

            $this->redirect('/checkout');
        }
    }

    /**
     * Display order details to its owner or an administrator.
     */
    public function show(string $orderId): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        if (!ctype_digit($orderId) || (int) $orderId <= 0) {
            $this->render('pages/404');
            return;
        }

        $orderModel = new Order();

        $order = $orderModel->getOrderWithDetails(
            (int) $orderId
        );

        if (!$order) {
            $this->render('pages/404');
            return;
        }

        $isOwner = (int) $order['user_id'] === (int) Auth::id();
        $isAdmin = Auth::isAdmin();

        if (!$isOwner && !$isAdmin) {
            $this->withError(
                'អ្នកគ្មានសិទ្ធិមើលការបញ្ជាទិញនេះទេ។'
            );
            $this->redirect('/');
            return;
        }

        $this->render('student/order_detail', [
            'order' => $order,
            'title' => 'ការបញ្ជាទិញ #' .
                $order['order_number'] .
                ' — ក្តឹបកូដ Academy',
        ]);
    }

    /**
     * Upload a receipt for an unpaid order.
     */
    public function uploadReceipt(string $orderId): void
    {
        if (!$this->requireLogin()) {
            return;
        }

        if (!$this->validateCsrf()) {
            return;
        }

        if (!ctype_digit($orderId) || (int) $orderId <= 0) {
            $this->render('pages/404');
            return;
        }

        $orderId = (int) $orderId;

        $orderModel = new Order();
        $order = $orderModel->find($orderId);

        // Only the owner can upload a receipt.
        if (
            !$order ||
            (int) $order['user_id'] !== (int) Auth::id()
        ) {
            $this->withError(
                'រកមិនឃើញការបញ្ជាទិញ ឬអ្នកគ្មានសិទ្ធិទេ។'
            );
            $this->redirect('/student/orders');
            return;
        }

        if ($order['payment_status'] === 'paid') {
            $this->withWarning(
                'ការបញ្ជាទិញនេះត្រូវបានទូទាត់រួចរាល់ហើយ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        if (
            !in_array(
                $order['payment_status'],
                ['pending', 'rejected'],
                true
            )
        ) {
            $this->withWarning(
                'ស្ថានភាពការបញ្ជាទិញនេះមិនអនុញ្ញាតឱ្យ Upload បង្កាន់ដៃទេ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        if (empty($order['payment_method_id'])) {
            $this->withError(
                'ការបញ្ជាទិញនេះមិនមានវិធីសាស្ត្រទូទាត់ទេ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        $file = $this->request->file('receipt');

        if (
            !$file ||
            !isset($file['error']) ||
            $file['error'] !== UPLOAD_ERR_OK
        ) {
            $this->withError(
                'សូមជ្រើសរើសរូបភាពបង្កាន់ដៃដែលត្រឹមត្រូវ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        $txnRef = trim(
            (string) $this->request->input(
                'transaction_reference',
                ''
            )
        );

        if (mb_strlen($txnRef) > 150) {
            $this->withError(
                'លេខយោងប្រតិបត្តិការវែងពេក។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        // Upload::receipt must validate the real file type,
        // file size, upload errors, and generate a safe filename.
        $uploadRes = Upload::receipt($file);

        if (
            empty($uploadRes['success']) ||
            empty($uploadRes['filename'])
        ) {
            $this->withError(
                $uploadRes['error'] ??
                'មិនអាច Upload បង្កាន់ដៃបានទេ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        $paymentModel = new Payment();
        $paymentMethodModel = new PaymentMethod();

        $existing = $paymentModel->findByOrder($orderId);

        $paymentMethod = $paymentMethodModel->find(
            (int) $order['payment_method_id']
        );

        $paymentMethodName = $paymentMethod
            ? $paymentMethod['name']
            : 'Manual Transfer';

        $orderTotal = round(
            (float) $order['total_amount'],
            2
        );

        if (!is_finite($orderTotal) || $orderTotal <= 0) {
            $this->withError(
                'ចំនួនទឹកប្រាក់ក្នុងការបញ្ជាទិញមិនត្រឹមត្រូវ។'
            );
            $this->redirect('/order/' . $orderId);
            return;
        }

        $orderDb = $orderModel->getDb();

        try {
            // Payment and order changes must share this PDO connection.
            $orderDb->beginTransaction();

            if ($existing) {
                // Do not replace a payment already verified as paid.
                if ($existing['status'] === 'paid') {
                    throw new \RuntimeException(
                        'Payment already verified.'
                    );
                }

                $updated = $paymentModel->update(
                    (int) $existing['id'],
                    [
                        'receipt_image' => $uploadRes['filename'],
                        'transaction_reference' => $txnRef !== ''
                            ? $txnRef
                            : ($existing['transaction_reference'] ?? null),
                        'status' => 'pending',
                        'rejection_reason' => null,
                    ]
                );

                if ($updated === false) {
                    throw new \RuntimeException(
                        'Payment update failed.'
                    );
                }
            } else {
                $created = $paymentModel->create([
                    'order_id' => $orderId,
                    'user_id' => (int) Auth::id(),
                    'payment_method' => $paymentMethodName,
                    'transaction_reference' => $txnRef !== ''
                        ? $txnRef
                        : null,
                    'amount' => $orderTotal,
                    'receipt_image' => $uploadRes['filename'],
                    'status' => 'pending',
                ]);

                if (!$created) {
                    throw new \RuntimeException(
                        'Payment creation failed.'
                    );
                }
            }

            // Receipt submission is NOT proof of payment.
            // Keep the order pending until an authorized admin
            // verifies the transaction.
            $updatedOrder = $orderModel->update(
                $orderId,
                ['payment_status' => 'pending']
            );

            if ($updatedOrder === false) {
                throw new \RuntimeException(
                    'Order status update failed.'
                );
            }

            $orderDb->commit();

            $this->withSuccess(
                'បាន Upload បង្កាន់ដៃដោយជោគជ័យ។ សូមរង់ចាំ Admin ផ្ទៀងផ្ទាត់។'
            );

            $this->redirect('/order/' . $orderId);
            return;

        } catch (\Throwable $e) {
            if ($orderDb->inTransaction()) {
                $orderDb->rollBack();
            }

            error_log(
                '[OrderController::uploadReceipt] ' .
                $e->getMessage()
            );

            $this->withError(
                'មិនអាចរក្សាទុកបង្កាន់ដៃបានទេ។ សូមព្យាយាមម្ដងទៀត។'
            );

            $this->redirect('/order/' . $orderId);
        }
    }
}
