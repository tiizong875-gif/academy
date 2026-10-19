<?php
namespace App\Models;

class CourseSection extends Model {
    protected string $table = 'course_sections';

    public function getForCourse(int $courseId): array {
        return $this->where('course_id', $courseId, 'sort_order ASC, id ASC');
    }

    public function getWithLessons(int $courseId): array {
        $sections = $this->getForCourse($courseId);
        $lessonModel = new Lesson();

        foreach ($sections as &$sec) {
            $sec['lessons'] = $lessonModel->getForSection($sec['id']);
        }
        return $sections;
    }
}
