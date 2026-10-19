<?php
namespace App\Controllers\Admin;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Helpers\Str;
use App\Helpers\Upload;

class LessonAdminController extends AdminController {

    /**
     * Top-level short lessons management list with course filter, search, & pagination
     */
    public function index(): void {
        $courseModel = new Course();
        $lessonModel = new Lesson();

        $page = max(1, (int)$this->request->input('page', 1));
        $filters = [
            'course_id'    => $this->request->input('course_id', ''),
            'is_published' => $this->request->input('is_published', ''),
            'search'       => trim((string)$this->request->input('search', ''))
        ];

        $lessonsData = $lessonModel->getAllWithFilters($filters, $page, 15);
        $courses = $courseModel->all('id DESC');

        $this->renderAdmin('admin/lessons/index', [
            'lessons'    => $lessonsData['items'],
            'pagination' => $lessonsData,
            'courses'    => $courses,
            'filters'    => $filters,
            'title'      => 'គ្រប់គ្រងវីដេអូមេរៀន (Short Video Lessons)'
        ]);
    }

    /**
     * Create lesson: either standalone (/admin/lessons/create) or within course/section
     */
    public function create(?string $courseId = null, ?string $sectionId = null): void {
        $courseModel = new Course();
        $sectionModel = new CourseSection();
        $courses = $courseModel->all('id DESC');

        $selectedCourse = null;
        $selectedSection = null;
        $sections = [];

        if ($courseId) {
            $selectedCourse = $courseModel->find((int)$courseId);
            if ($selectedCourse) {
                $sections = $sectionModel->getForCourse((int)$courseId);
            }
        }

        if ($sectionId) {
            $selectedSection = $sectionModel->find((int)$sectionId);
        }

        // If no course preselected, default to the first available course if any
        if (!$selectedCourse && !empty($courses)) {
            $selectedCourse = $courses[0];
            $sections = $sectionModel->getForCourse((int)$selectedCourse['id']);
        }

        $this->renderAdmin('admin/lessons/create', [
            'courses'         => $courses,
            'sections'        => $sections,
            'selectedCourse'  => $selectedCourse,
            'selectedSection' => $selectedSection,
            'course'          => $selectedCourse, // backwards compat
            'section'         => $selectedSection, // backwards compat
            'title'           => 'បន្ថែមវីដេអូមេរៀនថ្មី'
        ]);
    }

    /**
     * Store new lesson
     */
    public function store(?string $courseId = null, ?string $sectionId = null): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $courseId = (int)($this->request->input('course_id') ?: $courseId);
        $sectionId = (int)($this->request->input('section_id') ?: $sectionId);

        if (!$courseId) {
            $this->withError('សូមជ្រើសរើសវគ្គសិក្សា');
            $this->redirectBack();
            return;
        }

        // Ensure there is at least one section for this course. If not, auto-create a default section
        $sectionModel = new CourseSection();
        if (!$sectionId) {
            $existingSections = $sectionModel->getForCourse($courseId);
            if (!empty($existingSections)) {
                $sectionId = (int)$existingSections[0]['id'];
            } else {
                $sectionId = $sectionModel->create([
                    'course_id'  => $courseId,
                    'title'      => 'មេរៀនទូទៅ',
                    'sort_order' => 1
                ]);
            }
        }

        $title = trim((string)$this->request->input('title', ''));
        $description = trim((string)$this->request->input('description', ''));
        $videoType = $this->request->input('video_type', 'upload');
        $videoUrl = trim((string)$this->request->input('video_url', ''));
        $durationSeconds = (int)$this->request->input('duration_seconds', 0);
        $sortOrder = (int)$this->request->input('sort_order', 1);
        $isFreePreview = $this->request->input('is_free_preview') ? 1 : 0;
        $isPublished = $this->request->input('is_published') ? 1 : 0;

        if (empty($title)) {
            $this->withError('សូមបំពេញចំណងជើងមេរៀន');
            $this->redirectBack();
            return;
        }

        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'lesson-' . time();
        }
        $lessonModel = new Lesson();
        if ($lessonModel->findByCourseAndSlug($courseId, $slug)) {
            $slug .= '-' . time();
        }

        // Handle Video File Upload
        $videoFile = null;
        $file = $this->request->file('video_file');
        if ($file && !empty($file['name'])) {
            $videoUpload = Upload::video($file);
            if (!$videoUpload['success']) {
                $this->withError($videoUpload['error']);
                $this->redirectBack();
                return;
            }
            $videoFile = $videoUpload['filename'];
            $videoType = 'upload';
        }

        // Handle Thumbnail Upload
        $thumbnailPath = null;
        $thumbFile = $this->request->file('thumbnail');
        if ($thumbFile && !empty($thumbFile['name'])) {
            $thumbUpload = Upload::thumbnail($thumbFile);
            if ($thumbUpload['success']) {
                $thumbnailPath = $thumbUpload['path'];
            } else {
                $this->withError($thumbUpload['error']);
                $this->redirectBack();
                return;
            }
        }

        $lessonId = $lessonModel->create([
            'section_id'       => $sectionId,
            'course_id'        => $courseId,
            'title'            => $title,
            'slug'             => $slug,
            'description'      => $description ?: null,
            'video_type'       => $videoType,
            'video_url'        => $videoUrl ?: null,
            'video_file'       => $videoFile,
            'thumbnail'        => $thumbnailPath,
            'duration_seconds' => $durationSeconds,
            'sort_order'       => $sortOrder,
            'is_free_preview'  => $isFreePreview,
            'is_published'     => $isPublished
        ]);

        // Sync course totals
        $courseModel = new Course();
        $courseModel->syncStats($courseId);

        $this->logAction('CREATE_LESSON', 'Lesson', $lessonId, "បានបន្ថែមមេរៀនវីដេអូ៖ {$title}");
        $this->withSuccess('បានបន្ថែមមេរៀនវីដេអូដោយជោគជ័យ!');
        $this->redirect('/admin/lessons');
    }

    /**
     * Edit lesson: support both /admin/lessons/{id}/edit and /admin/courses/{cId}/lessons/{lId}/edit
     */
    public function edit(string $firstParam, ?string $secondParam = null): void {
        $lessonModel = new Lesson();
        $courseModel = new Course();
        $sectionModel = new CourseSection();

        $lessonId = $secondParam ? (int)$secondParam : (int)$firstParam;
        $lesson = $lessonModel->find($lessonId);

        if (!$lesson) {
            $this->render('pages/404');
            return;
        }

        $courseId = (int)$lesson['course_id'];
        $course = $courseModel->find($courseId);
        $courses = $courseModel->all('id DESC');
        $sections = $sectionModel->getForCourse($courseId);

        $this->renderAdmin('admin/lessons/edit', [
            'lesson'   => $lesson,
            'course'   => $course,
            'courses'  => $courses,
            'sections' => $sections,
            'title'    => 'កែប្រែមេរៀនវីដេអូ៖ ' . $lesson['title']
        ]);
    }

    /**
     * Update lesson
     */
    public function update(string $firstParam, ?string $secondParam = null): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $lessonModel = new Lesson();
        $lessonId = $secondParam ? (int)$secondParam : (int)$firstParam;
        $lesson = $lessonModel->find($lessonId);

        if (!$lesson) {
            $this->render('pages/404');
            return;
        }

        $courseId = (int)($this->request->input('course_id') ?: $lesson['course_id']);
        $sectionId = (int)$this->request->input('section_id', $lesson['section_id']);
        $title = trim((string)$this->request->input('title', ''));
        $description = trim((string)$this->request->input('description', ''));
        $videoType = $this->request->input('video_type', $lesson['video_type']);
        $videoUrl = trim((string)$this->request->input('video_url', ''));
        $durationSeconds = (int)$this->request->input('duration_seconds', 0);
        $sortOrder = (int)$this->request->input('sort_order', 1);
        $isFreePreview = $this->request->input('is_free_preview') ? 1 : 0;
        $isPublished = $this->request->input('is_published') ? 1 : 0;

        if (empty($title)) {
            $this->withError('សូមបំពេញចំណងជើងមេរៀន');
            $this->redirectBack();
            return;
        }

        $updateData = [
            'course_id'        => $courseId,
            'section_id'       => $sectionId,
            'title'            => $title,
            'description'      => $description ?: null,
            'video_type'       => $videoType,
            'video_url'        => $videoUrl ?: null,
            'duration_seconds' => $durationSeconds,
            'sort_order'       => $sortOrder,
            'is_free_preview'  => $isFreePreview,
            'is_published'     => $isPublished
        ];

        // Replace video file if uploaded
        $file = $this->request->file('video_file');
        if ($file && !empty($file['name'])) {
            $videoUpload = Upload::video($file);
            if (!$videoUpload['success']) {
                $this->withError($videoUpload['error']);
                $this->redirectBack();
                return;
            }
            $updateData['video_file'] = $videoUpload['filename'];
            $updateData['video_type'] = 'upload';
        }

        // Replace thumbnail if uploaded
        $thumbFile = $this->request->file('thumbnail');
        if ($thumbFile && !empty($thumbFile['name'])) {
            $thumbUpload = Upload::thumbnail($thumbFile);
            if ($thumbUpload['success']) {
                $updateData['thumbnail'] = $thumbUpload['path'];
            } else {
                $this->withError($thumbUpload['error']);
                $this->redirectBack();
                return;
            }
        }

        $lessonModel->update($lessonId, $updateData);

        // Sync old & new course stats
        $courseModel = new Course();
        $courseModel->syncStats($courseId);
        if ($courseId !== (int)$lesson['course_id']) {
            $courseModel->syncStats((int)$lesson['course_id']);
        }

        $this->logAction('UPDATE_LESSON', 'Lesson', $lessonId, "បានកែប្រែមេរៀនវីដេអូ៖ {$title}");
        $this->withSuccess('បានរក្សាទុកការកែប្រែមេរៀនវីដេអូ!');
        $this->redirect('/admin/lessons');
    }

    /**
     * Delete lesson
     */
    public function delete(string $firstParam, ?string $secondParam = null): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $lessonModel = new Lesson();
        $lessonId = $secondParam ? (int)$secondParam : (int)$firstParam;
        $lesson = $lessonModel->find($lessonId);

        if ($lesson) {
            $courseId = (int)$lesson['course_id'];
            $lessonModel->delete($lessonId);

            $courseModel = new Course();
            $courseModel->syncStats($courseId);

            $this->logAction('DELETE_LESSON', 'Lesson', $lessonId, "បានលុបមេរៀនវីដេអូ៖ {$lesson['title']}");
            $this->withSuccess('បានលុបមេរៀនដោយជោគជ័យ!');
        }

        $this->redirect('/admin/lessons');
    }

    /**
     * Toggle publish / unpublish status
     */
    public function togglePublish(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $lessonModel = new Lesson();
        $lesson = $lessonModel->find((int)$id);

        if ($lesson) {
            $newStatus = $lesson['is_published'] ? 0 : 1;
            $lessonModel->update((int)$id, ['is_published' => $newStatus]);

            $courseModel = new Course();
            $courseModel->syncStats((int)$lesson['course_id']);

            $statusKh = $newStatus ? 'ផ្សព្វផ្សាយ (Published)' : 'ផ្អាកផ្សាយ (Hidden)';
            $this->logAction('TOGGLE_LESSON', 'Lesson', (int)$id, "បានប្តូរស្ថានភាពមេរៀន៖ {$statusKh}");
            $this->withSuccess("បានប្តូរស្ថានភាពមេរៀនទៅជា {$statusKh}!");
        }

        $this->redirectBack();
    }

    /**
     * AJAX endpoint to fetch sections of a course
     */
    public function getSections(string $courseId): void {
        $sectionModel = new CourseSection();
        $sections = $sectionModel->getForCourse((int)$courseId);
        $this->json(['success' => true, 'sections' => $sections]);
    }
}
