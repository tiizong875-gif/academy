<?php
namespace App\Controllers\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Helpers\Auth;

class OrderAdminController extends AdminController {
    public function index(): void {
        $orderModel = new Order();
        $page = max(1, (int)$this->request->input('page', 1));
        $status = $this->request->input('status');
        $search = trim((string)$this->request->input('q', ''));

        $ordersData = $orderModel->getAllOrders($page, 15, [
            'status' => $status,
            'search' => $search
        ]);

        $this->renderAdmin('admin/orders/index', [
            'orders'     => $ordersData['items'],
            'pagination' => $ordersData,
            'status'     => $status,
            'search'     => $search,
            'title'      => 'គ្រប់គ្រងការបញ្ជាទិញ & ការបង់ប្រាក់ (Orders & Payments)'
        ]);
    }

    public function show(string $id): void {
        $id = (int)$id;
        $orderModel = new Order();
        $order = $orderModel->getOrderWithDetails($id);

        if (!$order) {
            $this->render('pages/404');
            return;
        }

        $this->renderAdmin('admin/orders/show', [
            'order' => $order,
            'title' => 'ព័ត៌មានលម្អិតការបញ្ជាទិញ #' . $order['order_number']
        ]);
    }

    public function approvePayment(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $orderId = (int)$id;
        $adminId = (int)Auth::id();

        $result = \App\Services\PaymentVerificationService::processVerifiedPayment($orderId, $adminId, null, 'manual_admin_review');

        if ($result['success']) {
            $this->withSuccess("បានអនុម័តការបង់ប្រាក់សម្រាប់វិក្កយបត្រដោយជោគជ័យ!");
        } else {
            $this->withError('មានបញ្ហាក្នុងការអនុម័ត៖ ' . ($result['error'] ?? 'បរាជ័យ'));
        }

        $this->redirect('/admin/orders/' . $orderId);
    }

    public function rejectPayment(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $orderId = (int)$id;
        $orderModel = new Order();
        $order = $orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            $this->render('pages/404');
            return;
        }

        $reason = trim((string)$this->request->input('rejection_reason', 'ព័ត៌មានផ្ទេរប្រាក់មិនត្រឹមត្រូវ ឬរកមិនឃើញប្រតិបត្តិការ'));

        $db = $orderModel->getDb();
        try {
            $db->beginTransaction();

            $adminId = Auth::id();

            // Update Order status
            $orderModel->update($orderId, [
                'payment_status' => 'rejected',
                'admin_notes'    => "បដិសេធ៖ {$reason}"
            ]);

            // Update Payment record
            $paymentModel = new Payment();
            $payment = $paymentModel->findByOrder($orderId);

            if ($payment) {
                $paymentModel->update($payment['id'], [
                    'status'           => 'rejected',
                    'admin_id'         => $adminId,
                    'rejection_reason' => $reason
                ]);
            }

            $db->commit();

            $this->logAction('REJECT_PAYMENT', 'Order', $orderId, "បានបដិសេធការបង់ប្រាក់ #{$order['order_number']} មូលហេតុ៖ {$reason}");
            $this->withWarning("បានបដិសេធការបង់ប្រាក់សម្រាប់វិក្កយបត្រ #{$order['order_number']}");
            $this->redirect('/admin/orders/' . $orderId);

        } catch (\Exception $e) {
            $db->rollBack();
            $this->withError('មានបញ្ហាក្នុងការបដិសេធ៖ ' . $e->getMessage());
            $this->redirect('/admin/orders/' . $orderId);
        }
    }
}
