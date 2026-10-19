<?php
namespace App\Models;

class Lesson extends Model {
    protected string $table = 'lessons';

    public function getForSection(int $sectionId): array {
        return $this->where('section_id', $sectionId, 'sort_order ASC, id ASC');
    }

    public function getForCourse(int $courseId): array {
        return $this->where('course_id', $courseId, 'sort_order ASC, id ASC');
    }

    public function findByCourseAndSlug(int $courseId, string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE course_id = :cid AND slug = :slug LIMIT 1");
        $stmt->execute(['cid' => $courseId, 'slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getFirstLesson(int $courseId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE course_id = :cid AND is_published = 1 ORDER BY sort_order ASC, id ASC LIMIT 1");
        $stmt->execute(['cid' => $courseId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getPreviousAndNext(int $courseId, int $currentLessonId): array {
        $all = $this->where('course_id', $courseId, 'sort_order ASC, id ASC');
        $prev = null;
        $next = null;

        for ($i = 0; $i < count($all); $i++) {
            if ($all[$i]['id'] == $currentLessonId) {
                if ($i > 0) {
                    $prev = $all[$i - 1];
                }
                if ($i < count($all) - 1) {
                    $next = $all[$i + 1];
                }
                break;
            }
        }

        return ['prev' => $prev, 'next' => $next];
    }

    public function getAllWithFilters(array $filters = [], int $page = 1, int $perPage = 15): array {
        $where = ["1=1"];
        $params = [];

        if (!empty($filters['course_id'])) {
            $where[] = "l.course_id = :course_id";
            $params['course_id'] = (int)$filters['course_id'];
        }

        if (!empty($filters['section_id'])) {
            $where[] = "l.section_id = :section_id";
            $params['section_id'] = (int)$filters['section_id'];
        }

        if (isset($filters['is_published']) && $filters['is_published'] !== '') {
            $where[] = "l.is_published = :is_published";
            $params['is_published'] = (int)$filters['is_published'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(l.title LIKE :search OR l.description LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $whereClause = implode(' AND ', $where);

        $countSql = "SELECT COUNT(*) FROM `{$this->table}` l WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT l.*, c.title as course_title, c.slug as course_slug, cs.title as section_title
                FROM `{$this->table}` l
                JOIN `courses` c ON l.course_id = c.id
                JOIN `course_sections` cs ON l.section_id = cs.id
                WHERE {$whereClause}
                ORDER BY c.id DESC, cs.sort_order ASC, l.sort_order ASC, l.id ASC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        $totalPages = (int)ceil($total / $perPage);

        return [
            'items'        => $items,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'has_prev'     => $page > 1,
            'has_next'     => $page < $totalPages
        ];
    }
}
