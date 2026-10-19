<?php
namespace App\Models;

class Review extends Model {
    protected string $table = 'reviews';

    public function getForCourse(int $courseId): array {
        $sql = "SELECT r.*, u.name as user_name, u.avatar as user_avatar
                FROM `{$this->table}` r
                JOIN `users` u ON r.user_id = u.id
                WHERE r.course_id = :cid AND r.is_approved = 1
                ORDER BY r.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $courseId]);
        return $stmt->fetchAll();
    }

    public function hasReviewed(int $userId, int $courseId): bool {
        $stmt = $this->db->prepare("SELECT id FROM `{$this->table}` WHERE user_id = :uid AND course_id = :cid LIMIT 1");
        $stmt->execute(['uid' => $userId, 'cid' => $courseId]);
        return (bool)$stmt->fetchColumn();
    }

    public function getAllReviews(int $page = 1, int $perPage = 15): array {
        $countSql = "SELECT COUNT(*) FROM `{$this->table}`";
        $total = (int)$this->db->query($countSql)->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT r.*, u.name as user_name, u.email as user_email, c.title as course_title, c.slug as course_slug
                FROM `{$this->table}` r
                JOIN `users` u ON r.user_id = u.id
                JOIN `courses` c ON r.course_id = c.id
                ORDER BY r.id DESC
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
