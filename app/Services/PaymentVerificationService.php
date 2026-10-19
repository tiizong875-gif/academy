<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\AdminActivityLog;

class PaymentVerificationService {
    /**
     * Complete and activate a verified payment (called by either automated API webhook or authorized manual admin review).
     * Strictly idempotent: will not double-process already paid orders.
     */
    public static function processVerifiedPayment(int $orderId, ?int $adminId = null, ?string $transactionRef = null, string $verificationSource = 'manual_review'): array {
        $orderModel = new Order();
        $order = $orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            return ['success' => false, 'error' => 'Order not found'];
        }

        // Idempotency: If already paid, do not re-process
        if ($order['payment_status'] === 'paid') {
            return ['success' => true, 'already_paid' => true, 'message' => 'Order was already verified and paid.'];
        }

        $db = $orderModel->getDb();
        try {
            $db->beginTransaction();

            // 1. Update Order state to paid
            $notes = $adminId 
                ? "បានផ្ទៀងផ្ទាត់និងអនុម័តដោយ Admin ID #{$adminId} ({$verificationSource})" 
                : "បានផ្ទៀងផ្ទាត់ដោយស្វ័យប្រវត្តិតាមរយៈប្រព័ន្ធ ({$verificationSource})";

            $orderModel->update($orderId, [
                'payment_status' => 'paid',
                'admin_notes'    => $notes
            ]);

            // 2. Update or insert Payment record
            $paymentModel = new Payment();
            $payment = $paymentModel->findByOrder($orderId);

            $paymentData = [
                'status'                => 'approved',
                'admin_id'              => $adminId,
                'verified_at'           => date('Y-m-d H:i:s'),
                'transaction_reference' => $transactionRef ?: ($payment['transaction_reference'] ?? null)
            ];

            if ($payment) {
                $paymentModel->update($payment['id'], $paymentData);
            } else {
                $paymentModel->create(array_merge($paymentData, [
                    'order_id'       => $orderId,
                    'user_id'        => $order['user_id'],
                    'payment_method' => $order['payment_method_name'] ?? 'KHQR',
                    'amount'         => $order['total_amount']
                ]));
            }

            // 3. Atomically activate enrollments for all courses in this order
            $enrollmentModel = new Enrollment();
            foreach ($order['items'] as $item) {
                if (!empty($item['course_id'])) {
                    $enrollmentModel->enrollUser($order['user_id'], (int)$item['course_id'], $orderId);
                }
            }

            $db->commit();

            // 4. Audit Log
            AdminActivityLog::log(
                'VERIFY_PAYMENT',
                'Order',
                $orderId,
                "Payment verified ({$verificationSource}) for Order #{$order['order_number']}, Amount: \${$order['total_amount']}"
            );

            return ['success' => true, 'order' => $order];

        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log('[PaymentVerificationService] Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
