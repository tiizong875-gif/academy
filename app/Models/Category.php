<?php
namespace App\Models;

class Category extends Model {
    protected string $table = 'categories';

    public function getActive(): array {
        return $this->where('is_active', 1, 'sort_order ASC, id ASC');
    }

    public function findBySlug(string $slug): ?array {
        return $this->findBy('slug', $slug);
    }

    public function getWithCourseCount(): array {
        $sql = "SELECT c.*, COUNT(crs.id) as courses_count 
                FROM `{$this->table}` c
                LEFT JOIN `courses` crs ON c.id = crs.category_id AND crs.status = 'published'
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
