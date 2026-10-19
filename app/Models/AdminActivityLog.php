<?php
namespace App\Models;

use App\Helpers\Auth;

class AdminActivityLog extends Model {
    protected string $table = 'admin_activity_logs';

    public static function log(string $action, ?string $targetType = null, ?int $targetId = null, ?string $details = null): void {
        $model = new self();
        $adminId = Auth::id();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $model->create([
            'admin_id'    => $adminId,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'details'     => $details,
            'ip_address'  => $ip
        ]);
    }

    public function getLogs(int $page = 1, int $perPage = 20): array {
        $countSql = "SELECT COUNT(*) FROM `{$this->table}`";
        $total = (int)$this->db->query($countSql)->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT l.*, u.name as admin_name, u.email as admin_email
                FROM `{$this->table}` l
                LEFT JOIN `users` u ON l.admin_id = u.id
                ORDER BY l.id DESC
                LIMIT {$perPage} OFFSET {$offset}";
        $items = $this->db->query($sql)->fetchAll();

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
