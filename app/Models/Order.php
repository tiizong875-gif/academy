<?php
namespace App\Models;

class Order extends Model {
    protected string $table = 'orders';

    public function generateOrderNumber(): string {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    public function getUserOrders(int $userId): array {
        $sql = "SELECT o.*, pm.name as payment_method_name,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as items_count
                FROM `{$this->table}` o
                LEFT JOIN `payment_methods` pm ON o.payment_method_id = pm.id
                WHERE o.user_id = :uid
                ORDER BY o.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getOrderWithDetails(int $orderId): ?array {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone,
                       pm.name as payment_method_name, pm.account_name, pm.account_number, pm.qr_image, pm.instructions
                FROM `{$this->table}` o
                JOIN `users` u ON o.user_id = u.id
                LEFT JOIN `payment_methods` pm ON o.payment_method_id = pm.id
                WHERE o.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $orderId]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        // Get items
        $itemSql = "SELECT oi.*, COALESCE(c.title, 'វគ្គសិក្សាដែលបានលុប') as title, 
                           COALESCE(c.title_kh, 'វគ្គសិក្សាដែលបានលុប') as title_kh, 
                           c.slug, c.thumbnail
                    FROM `order_items` oi
                    LEFT JOIN `courses` c ON oi.course_id = c.id
                    WHERE oi.order_id = :oid";
        $itemStmt = $this->db->prepare($itemSql);
        $itemStmt->execute(['oid' => $orderId]);
        $order['items'] = $itemStmt->fetchAll();

        // Get payment record if exists
        $paymentSql = "SELECT * FROM `payments` WHERE order_id = :oid ORDER BY id DESC LIMIT 1";
        $payStmt = $this->db->prepare($paymentSql);
        $payStmt->execute(['oid' => $orderId]);
        $order['payment'] = $payStmt->fetch() ?: null;

        return $order;
    }

    public function getAllOrders(int $page = 1, int $perPage = 15, array $filters = []): array {
        $where = ["1=1"];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "o.payment_status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(o.order_number LIKE :s OR u.name LIKE :s OR u.email LIKE :s)";
            $params['s'] = "%{$filters['search']}%";
        }

        $whereClause = implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) FROM `{$this->table}` o JOIN `users` u ON o.user_id = u.id WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, pm.name as payment_method_name,
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as items_count,
                       (SELECT receipt_image FROM payments p WHERE p.order_id = o.id ORDER BY p.id DESC LIMIT 1) as receipt_image
                FROM `{$this->table}` o
                JOIN `users` u ON o.user_id = u.id
                LEFT JOIN `payment_methods` pm ON o.payment_method_id = pm.id
                WHERE {$whereClause}
                ORDER BY o.id DESC
                LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / $perPage)
        ];
    }
}
