<?php
namespace App\Models;

class Wishlist extends Model {
    protected string $table = 'wishlists';

    public function isInWishlist(int $userId, int $courseId): bool {
        $stmt = $this->db->prepare("SELECT id FROM `{$this->table}` WHERE user_id = :uid AND course_id = :cid LIMIT 1");
        $stmt->execute(['uid' => $userId, 'cid' => $courseId]);
        return (bool)$stmt->fetchColumn();
    }

    public function toggle(int $userId, int $courseId): bool {
        if ($this->isInWishlist($userId, $courseId)) {
            $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE user_id = :uid AND course_id = :cid");
            $stmt->execute(['uid' => $userId, 'cid' => $courseId]);
            return false; // removed
        } else {
            $this->create(['user_id' => $userId, 'course_id' => $courseId]);
            return true; // added
        }
    }
}
