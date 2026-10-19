<?php
namespace App\Models;

class Enrollment extends Model {
    protected string $table = 'enrollments';

    public function isEnrolled(int $userId, int $courseId): bool {
        $stmt = $this->db->prepare("SELECT id FROM `{$this->table}` WHERE user_id = :uid AND course_id = :cid AND status = 'active' LIMIT 1");
        $stmt->execute(['uid' => $userId, 'cid' => $courseId]);
        return (bool)$stmt->fetchColumn();
    }

    public function enrollUser(int $userId, int $courseId, ?int $orderId = null): bool {
        if ($this->isEnrolled($userId, $courseId)) {
            return true;
        }

        return (bool)$this->create([
            'user_id'   => $userId,
            'course_id' => $courseId,
            'order_id'  => $orderId,
            'status'    => 'active'
        ]);
    }

    public function getUserCourses(int $userId): array {
        $sql = "SELECT e.*, c.title, c.title_kh, c.slug, c.thumbnail, c.duration_minutes, c.total_lessons,
                       cat.name_kh as category_name_kh, u.name as instructor_name,
                       (SELECT COUNT(*) FROM lesson_progress lp WHERE lp.user_id = e.user_id AND lp.course_id = e.course_id AND lp.is_completed = 1) as completed_lessons
                FROM `{$this->table}` e
                JOIN `courses` c ON e.course_id = c.id
                JOIN `categories` cat ON c.category_id = cat.id
                JOIN `users` u ON c.instructor_id = u.id
                WHERE e.user_id = :uid AND e.status = 'active'
                ORDER BY e.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $courses = $stmt->fetchAll();

        foreach ($courses as &$c) {
            $total = (int)$c['total_lessons'];
            $completed = (int)$c['completed_lessons'];
            $c['progress_percentage'] = $total > 0 ? min(100, round(($completed / $total) * 100)) : 0;
        }
        return $courses;
    }
}
