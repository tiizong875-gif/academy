<?php

namespace App\Models;

class Course extends Model {
    protected string $table = 'courses';

    private function getBaseSelect(): string {
        return "SELECT c.*, cat.name as category_name, cat.name_kh as category_name_kh, u.name as instructor_name, u.avatar as instructor_avatar, u.headline as instructor_headline, u.bio as instructor_bio 
                FROM `{$this->table}` c
                JOIN `categories` cat ON c.category_id = cat.id
                JOIN `users` u ON c.instructor_id = u.id";
    }

    public function findBySlug(string $slug): ?array {
        $sql = $this->getBaseSelect() . " WHERE c.slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findWithDetails(int $id): ?array {
        $sql =$this->getBaseSelect() . " WHERE c.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' =>$id]);
        $row =$stmt->fetch();
        return $row ?: null;
    }

    public function getFeatured(int $limit = 6): array {
        $limit = max(1,$limit);
        $sql =$this->getBaseSelect() . " 
                WHERE c.status = 'published' AND c.is_featured = 1
                ORDER BY c.id DESC LIMIT {$limit}";
        return $this->db->query($sql)->fetchAll();
    }

    public function getLatest(int $limit = 8): array {
        $limit = max(1,$limit);
        $sql =$this->getBaseSelect() . " 
                WHERE c.status = 'published'
                ORDER BY c.created_at DESC LIMIT {$limit}";
        return $this->db->query($sql)->fetchAll();
    }

    public function getPopular(int $limit = 6): array {
        $limit = max(1,$limit);
        $sql =$this->getBaseSelect() . " 
                WHERE c.status = 'published' AND c.is_popular = 1
                ORDER BY c.review_count DESC, c.id DESC LIMIT {$limit}";
        return $this->db->query($sql)->fetchAll();
    }

    public function getRelated(int $categoryId, int $excludeCourseId, int$limit = 3): array {
        $limit = max(1,$limit);
        $sql =$this->getBaseSelect() . " 
                WHERE c.status = 'published' AND c.category_id = :cat_id AND c.id != :ex_id
                ORDER BY c.id DESC LIMIT {$limit}";
        $stmt =$this->db->prepare($sql);$stmt->execute(['cat_id' => $categoryId, 'ex_id' =>$excludeCourseId]);
        return $stmt->fetchAll();
    }

    public function filterCourses(array $filters = [], int$page = 1, int $perPage = 9): array {$where = ["c.status = 'published'"];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = "(c.title LIKE :search OR c.title_kh LIKE :search OR c.short_description LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }

        if (!empty($filters['category_id'])) {$where[] = "c.category_id = :cat";
            $params['cat'] = (int)$filters['category_id'];
        }

        if (!empty($filters['level']) && in_array($filters['level'], ['beginner', 'intermediate', 'advanced'], true)) {$where[] = "c.level = :level";
            $params['level'] =$filters['level'];
        }

        if (isset($filters['price'])) {
            if ($filters['price'] === 'free') {$where[] = "(c.is_free = 1 OR c.price = 0)";
            } elseif ($filters['price'] === 'paid') {$where[] = "(c.is_free = 0 AND c.price > 0)";
            }
        }

        $whereClause = implode(' AND ',$where);

        $orderBy = "c.id DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'oldest':
                    $orderBy = "c.id ASC";
                    break;
                case 'price_low':
                    $orderBy = "COALESCE(c.discount_price, c.price) ASC";
                    break;
                case 'price_high':
                    $orderBy = "COALESCE(c.discount_price, c.price) DESC";
                    break;
                case 'popular':
                    $orderBy = "c.review_count DESC, c.id DESC";
                    break;
                case 'rating':
                    $orderBy = "c.average_rating DESC";
                    break;
            }
        }

        // Count total
        $countSql = "SELECT COUNT(*) FROM `{$this->table}` c WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Fetch paginated
        $page = max(1, $page);$perPage = max(1, $perPage);$offset = max(0, ($page - 1) *$perPage);

        $sql =$this->getBaseSelect() . " 
                WHERE {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $items =$stmt->fetchAll();

        $totalPages = (int)ceil($total / $perPage);

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'has_prev'     => $page > 1,
            'has_next'     => $page <$totalPages
        ];
    }

    public function syncStats(int $courseId): void {$sql = "SELECT COUNT(*) as total_lessons, COALESCE(SUM(duration_seconds), 0) as total_seconds
                FROM `lessons`
                WHERE course_id = :cid AND is_published = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' =>$courseId]);
        $res =$stmt->fetch();

        $minutes = (int)ceil(($res['total_seconds'] ?? 0) / 60);
        $lessons = (int)($res['total_lessons'] ?? 0);

        $this->update($courseId, [
            'total_lessons'    => $lessons,
            'duration_minutes' => $minutes
        ]);
    }
}