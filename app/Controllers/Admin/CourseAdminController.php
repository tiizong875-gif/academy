<?php
namespace App\Controllers\Admin;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\User;
use App\Helpers\Str;
use App\Helpers\Upload;

class CourseAdminController extends AdminController {
    public function index(): void {
        $courseModel = new Course();
        $categoryModel = new Category();

        $page = max(1, (int)$this->request->input('page', 1));
        $search = trim((string)$this->request->input('q', ''));
        $categoryId = $this->request->input('category');
        $status = $this->request->input('status');

        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(c.title LIKE :s OR c.title_kh LIKE :s)";
            $params['s'] = "%{$search}%";
        }

        if (!empty($categoryId)) {
            $where[] = "c.category_id = :cat";
            $params['cat'] = (int)$categoryId;
        }

        if (!empty($status)) {
            $where[] = "c.status = :status";
            $params['status'] = $status;
        }

        $whereClause = implode(' AND ', $where);
        $db = $courseModel->getDb();

        $countSql = "SELECT COUNT(*) FROM courses c WHERE {$whereClause}";
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT c.*, cat.name_kh as category_name_kh, u.name as instructor_name,
                       (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) as actual_lessons
                FROM courses c
                JOIN categories cat ON c.category_id = cat.id
                JOIN users u ON c.instructor_id = u.id
                WHERE {$whereClause}
                ORDER BY c.id DESC
                LIMIT {$perPage} OFFSET {$offset}";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll();

        $pagination = [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => (int)ceil($total / $perPage),
            'has_prev'     => $page > 1,
            'has_next'     => $page < ceil($total / $perPage)
        ];

        $categories = $categoryModel->all('sort_order ASC');

        $this->renderAdmin('admin/courses/index', [
            'courses'    => $courses,
            'pagination' => $pagination,
            'categories' => $categories,
            'filters'    => ['q' => $search, 'category' => $categoryId, 'status' => $status],
            'title'      => 'គ្រប់គ្រងវគ្គសិក្សា (Course Management)'
        ]);
    }

    public function create(): void {
        $categoryModel = new Category();
        $userModel = new User();

        $categories = $categoryModel->all('sort_order ASC');
        $instructors = $userModel->getInstructors();

        $this->renderAdmin('admin/courses/create', [
            'categories'  => $categories,
            'instructors' => $instructors,
            'title'       => 'បង្កើតវគ្គសិក្សាថ្មី'
        ]);
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $title = trim((string)$this->request->input('title', ''));
        $titleKh = trim((string)$this->request->input('title_kh', ''));
        $categoryId = (int)$this->request->input('category_id');
        $instructorId = (int)$this->request->input('instructor_id');
        $level = $this->request->input('level', 'beginner');
        $price = (float)$this->request->input('price', 0);
        $discountPrice = $this->request->input('discount_price') !== '' ? (float)$this->request->input('discount_price') : null;
        $isFree = $this->request->input('is_free') ? 1 : 0;
        $isFeatured = $this->request->input('is_featured') ? 1 : 0;
        $isPopular = $this->request->input('is_popular') ? 1 : 0;
        $status = $this->request->input('status', 'draft');
        $shortDesc = trim((string)$this->request->input('short_description', ''));
        $description = trim((string)$this->request->input('description', ''));
        $requirements = trim((string)$this->request->input('requirements', ''));
        $targetAudience = trim((string)$this->request->input('target_audience', ''));
        $learningObjectives = trim((string)$this->request->input('learning_objectives', ''));
        $videoPreviewUrl = trim((string)$this->request->input('video_preview_url', ''));

        if (empty($title) || empty($titleKh) || !$categoryId || !$instructorId) {
            $this->withError('សូមបំពេញព័ត៌មានដែលចាំបាច់ទាំងអស់ (ចំណងជើង, ប្រភេទ, គ្រូបង្រៀន)');
            $this->redirectBack();
            return;
        }

        $courseModel = new Course();
        $slug = Str::slug($title);
        // Ensure unique slug
        if ($courseModel->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $thumbnail = null;
        $file = $this->request->file('thumbnail');
        if ($file && !empty($file['name'])) {
            $upload = Upload::thumbnail($file);
            if ($upload['success']) {
                $thumbnail = $upload['filename'];
            } else {
                $this->withError($upload['error']);
                $this->redirectBack();
                return;
            }
        }

        $courseId = $courseModel->create([
            'category_id'         => $categoryId,
            'instructor_id'       => $instructorId,
            'title'               => $title,
            'title_kh'            => $titleKh,
            'slug'                => $slug,
            'short_description'   => $shortDesc ?: null,
            'description'         => $description ?: null,
            'requirements'        => $requirements ?: null,
            'target_audience'     => $targetAudience ?: null,
            'learning_objectives' => $learningObjectives ?: null,
            'level'               => $level,
            'price'               => $isFree ? 0.00 : $price,
            'discount_price'      => $isFree ? null : $discountPrice,
            'is_free'             => $isFree,
            'thumbnail'           => $thumbnail,
            'video_preview_url'   => $videoPreviewUrl ?: null,
            'is_featured'         => $isFeatured,
            'is_popular'          => $isPopular,
            'status'              => $status
        ]);

        $this->logAction('CREATE_COURSE', 'Course', $courseId, "បានបង្កើតវគ្គសិក្សាថ្មី៖ {$title}");
        $this->withSuccess('បានបង្កើតវគ្គសិក្សាដោយជោគជ័យ!');
        $this->redirect('/admin/courses/' . $courseId . '/sections');
    }

    public function edit(string $id): void {
        $courseModel = new Course();
        $categoryModel = new Category();
        $userModel = new User();

        $course = $courseModel->find((int)$id);
        if (!$course) {
            $this->render('pages/404');
            return;
        }

        $categories = $categoryModel->all('sort_order ASC');
        $instructors = $userModel->getInstructors();

        $this->renderAdmin('admin/courses/edit', [
            'course'      => $course,
            'categories'  => $categories,
            'instructors' => $instructors,
            'title'       => 'កែប្រែវគ្គសិក្សា៖ ' . $course['title']
        ]);
    }

    public function update(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if (!$course) {
            $this->render('pages/404');
            return;
        }

        $title = trim((string)$this->request->input('title', ''));
        $titleKh = trim((string)$this->request->input('title_kh', ''));
        $categoryId = (int)$this->request->input('category_id');
        $instructorId = (int)$this->request->input('instructor_id');
        $level = $this->request->input('level', 'beginner');
        $price = (float)$this->request->input('price', 0);
        $discountPrice = $this->request->input('discount_price') !== '' ? (float)$this->request->input('discount_price') : null;
        $isFree = $this->request->input('is_free') ? 1 : 0;
        $isFeatured = $this->request->input('is_featured') ? 1 : 0;
        $isPopular = $this->request->input('is_popular') ? 1 : 0;
        $status = $this->request->input('status', 'draft');
        $shortDesc = trim((string)$this->request->input('short_description', ''));
        $description = trim((string)$this->request->input('description', ''));
        $requirements = trim((string)$this->request->input('requirements', ''));
        $targetAudience = trim((string)$this->request->input('target_audience', ''));
        $learningObjectives = trim((string)$this->request->input('learning_objectives', ''));
        $videoPreviewUrl = trim((string)$this->request->input('video_preview_url', ''));

        $updateData = [
            'category_id'         => $categoryId,
            'instructor_id'       => $instructorId,
            'title'               => $title,
            'title_kh'            => $titleKh,
            'short_description'   => $shortDesc ?: null,
            'description'         => $description ?: null,
            'requirements'        => $requirements ?: null,
            'target_audience'     => $targetAudience ?: null,
            'learning_objectives' => $learningObjectives ?: null,
            'level'               => $level,
            'price'               => $isFree ? 0.00 : $price,
            'discount_price'      => $isFree ? null : $discountPrice,
            'is_free'             => $isFree,
            'video_preview_url'   => $videoPreviewUrl ?: null,
            'is_featured'         => $isFeatured,
            'is_popular'          => $isPopular,
            'status'              => $status
        ];

        $file = $this->request->file('thumbnail');
        if ($file && !empty($file['name'])) {
            $upload = Upload::thumbnail($file);
            if ($upload['success']) {
                $updateData['thumbnail'] = $upload['filename'];
            } else {
                $this->withError($upload['error']);
                $this->redirectBack();
                return;
            }
        }

        $courseModel->update($id, $updateData);
        $this->logAction('UPDATE_COURSE', 'Course', $id, "បានកែប្រែព័ត៌មានវគ្គសិក្សា៖ {$title}");
        $this->withSuccess('បានរក្សាទុកការកែប្រែវគ្គសិក្សាដោយជោគជ័យ!');
        $this->redirect('/admin/courses');
    }

    public function delete(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if ($course) {
            try {
                $courseModel->delete($id);
                $this->logAction('DELETE_COURSE', 'Course', $id, "បានលុបវគ្គសិក្សា៖ {$course['title']}");
                $this->withSuccess('បានលុបវគ្គសិក្សាដោយជោគជ័យ!');
            } catch (\Throwable $e) {
                // If hard delete fails, archive the course instead
                $courseModel->update($id, ['status' => 'archived']);
                $this->logAction('ARCHIVE_COURSE', 'Course', $id, "បានផ្ទេរវគ្គសិក្សាទៅកាន់បណ្ណសារ (Archived) ដោយសារមានប្រវត្តិបញ្ជាទិញ");
                $this->withWarning('វគ្គសិក្សានេះមានប្រវត្តិទិញដោយសិស្ស ដូច្នេះប្រព័ន្ធបានប្តូរស្ថានភាពទៅជា "បានរក្សាទុកក្នុងបណ្ណសារ (Archived)" ជំនួសវិញ ដើម្បីរក្សាសុវត្ថិភាពទិន្នន័យវិក្កយបត្រ!');
            }
        }

        $this->redirect('/admin/courses');
    }

    public function sections(string $courseId): void {
        $courseId = (int)$courseId;
        $courseModel = new Course();
        $course = $courseModel->find($courseId);

        if (!$course) {
            $this->render('pages/404');
            return;
        }

        $sectionModel = new CourseSection();
        $sections = $sectionModel->getWithLessons($courseId);

        $this->renderAdmin('admin/courses/sections', [
            'course'   => $course,
            'sections' => $sections,
            'title'    => 'គ្រប់គ្រងជំពូក & មេរៀន៖ ' . $course['title']
        ]);
    }

    public function createSection(string $courseId): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $courseId = (int)$courseId;
        $title = trim((string)$this->request->input('title', ''));
        $sortOrder = (int)$this->request->input('sort_order', 1);

        if (!empty($title)) {
            $sectionModel = new CourseSection();
            $sectionModel->create([
                'course_id'  => $courseId,
                'title'      => $title,
                'sort_order' => $sortOrder
            ]);
            $this->withSuccess('បានបន្ថែមជំពូកថ្មី!');
        }

        $this->redirect('/admin/courses/' . $courseId . '/sections');
    }

    public function deleteSection(string $courseId, string $sectionId): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $sectionModel = new CourseSection();
        $sectionModel->delete((int)$sectionId);

        $courseModel = new Course();
        $courseModel->syncStats((int)$courseId);

        $this->withSuccess('បានលុបជំពូកដោយជោគជ័យ!');
        $this->redirect('/admin/courses/' . $courseId . '/sections');
    }
}
