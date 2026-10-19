<?php
namespace App\Models;

class LessonProgress extends Model {
    protected string $table = 'lesson_progress';

    public function getProgress(int $userId, int $lessonId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE user_id = :uid AND lesson_id = :lid LIMIT 1");
        $stmt->execute(['uid' => $userId, 'lid' => $lessonId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getCompletedLessonIds(int $userId, int $courseId): array {
        $stmt = $this->db->prepare("SELECT lesson_id FROM `{$this->table}` WHERE user_id = :uid AND course_id = :cid AND is_completed = 1");
        $stmt->execute(['uid' => $userId, 'cid' => $courseId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
    }

    public function markCompleted(int $userId, int $courseId, int $lessonId, bool $completed = true): void {
        $existing = $this->getProgress($userId, $lessonId);
        if ($existing) {
            $this->update($existing['id'], [
                'is_completed' => $completed ? 1 : 0,
                'completed_at' => $completed ? date('Y-m-d H:i:s') : null
            ]);
        } else {
            $this->create([
                'user_id'      => $userId,
                'course_id'    => $courseId,
                'lesson_id'    => $lessonId,
                'is_completed' => $completed ? 1 : 0,
                'completed_at' => $completed ? date('Y-m-d H:i:s') : null
            ]);
        }
    }

    public function saveWatchTime(int $userId, int $courseId, int $lessonId, int $seconds): void {
        $existing = $this->getProgress($userId, $lessonId);
        if ($existing) {
            $this->update($existing['id'], [
                'last_watched_second' => $seconds
            ]);
        } else {
            $this->create([
                'user_id'             => $userId,
                'course_id'           => $courseId,
                'lesson_id'           => $lessonId,
                'last_watched_second' => $seconds,
                'is_completed'        => 0
            ]);
        }
    }
}
