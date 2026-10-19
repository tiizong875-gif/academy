<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\User;
use App\Helpers\Auth;
use App\Helpers\Upload;
use App\Helpers\Session;

class StudentController extends Controller {
    public function dashboard(): void {
        $userId = Auth::id();
        $enrollmentModel = new Enrollment();
        $orderModel = new Order();
        $lessonProgressModel = new LessonProgress();

        $enrolledCourses = $enrollmentModel->getUserCourses($userId);
        $orders = $orderModel->getUserOrders($userId);

        $totalEnrolled = count($enrolledCourses);
        $completedLessonsCount = $lessonProgressModel->count("user_id = :uid AND is_completed = 1", ['uid' => $userId]);

        // Continue learning course (first one with < 100% progress)
        $continueCourse = null;
        foreach ($enrolledCourses as $course) {
            if ($course['progress_percentage'] < 100) {
                $continueCourse = $course;
                break;
            }
        }
        if (!$continueCourse && !empty($enrolledCourses)) {
            $continueCourse = $enrolledCourses[0];
        }

        $this->render('student/dashboard', [
            'enrolledCourses'       => $enrolledCourses,
            'totalEnrolled'         => $totalEnrolled,
            'completedLessonsCount' => $completedLessonsCount,
            'recentOrders'          => array_slice($orders, 0, 5),
            'continueCourse'        => $continueCourse,
            'title'                 => 'ផ្ទាំងគ្រប់គ្រងការសិក្សា — ក្តឹបកូដ Academy'
        ]);
    }

    public function myCourses(): void {
        $userId = Auth::id();
        $enrollmentModel = new Enrollment();
        $courses = $enrollmentModel->getUserCourses($userId);

        $this->render('student/my_courses', [
            'courses' => $courses,
            'title'   => 'វគ្គសិក្សារបស់ខ្ញុំ — ក្តឹបកូដ Academy'
        ]);
    }

    public function orders(): void {
        $userId = Auth::id();
        $orderModel = new Order();
        $orders = $orderModel->getUserOrders($userId);

        $this->render('student/orders', [
            'orders' => $orders,
            'title'  => 'ប្រវត្តិបញ្ជាទិញ — ក្តឹបកូដ Academy'
        ]);
    }

    public function learn(string $courseSlug, ?string $lessonSlug = null): void {
        $userId = Auth::id();
        $courseModel = new Course();
        $sectionModel = new CourseSection();
        $lessonModel = new Lesson();
        $enrollmentModel = new Enrollment();
        $progressModel = new LessonProgress();

        $course = $courseModel->findBySlug($courseSlug);
        if (!$course || $course['status'] !== 'published') {
            $this->render('pages/404');
            return;
        }

        $isEnrolled = $enrollmentModel->isEnrolled($userId, $course['id']);

        // Fetch curriculum
        $sections = $sectionModel->getWithLessons($course['id']);

        // Determine current lesson
        $currentLesson = null;
        if ($lessonSlug) {
            $currentLesson = $lessonModel->findByCourseAndSlug($course['id'], $lessonSlug);
        }

        // If no lesson specified or invalid, pick first lesson or last watched
        if (!$currentLesson) {
            $currentLesson = $lessonModel->getFirstLesson($course['id']);
        }

        if (!$currentLesson) {
            $this->withWarning('វគ្គសិក្សានេះមិនទាន់មានមេរៀននៅឡើយទេ');
            $this->redirect('/course/' . $courseSlug);
            return;
        }

        // Check permission to view this specific lesson:
        // Must be enrolled OR course is free OR this lesson is a free preview
        $canWatch = $isEnrolled || $course['is_free'] || (bool)$currentLesson['is_free_preview'];

        if (!$canWatch) {
            $this->withWarning('មេរៀននេះត្រូវការទិញវគ្គសិក្សាដើម្បីមើល។ សូមចុះឈ្មោះចូលរៀនជាមុនសិន!');
            $this->redirect('/course/' . $courseSlug);
            return;
        }

        // Prev and Next
        $nav = $lessonModel->getPreviousAndNext($course['id'], $currentLesson['id']);

        // Get completed lessons list for this user and course
        $completedLessonIds = $progressModel->getCompletedLessonIds($userId, $course['id']);
        $currentProgress = $progressModel->getProgress($userId, $currentLesson['id']);

        $this->render('student/learn', [
            'course'             => $course,
            'sections'           => $sections,
            'currentLesson'      => $currentLesson,
            'isEnrolled'         => $isEnrolled,
            'prevLesson'         => $nav['prev'],
            'nextLesson'         => $nav['next'],
            'completedLessonIds' => $completedLessonIds,
            'currentProgress'    => $currentProgress,
            'title'              => $currentLesson['title'] . ' — ' . $course['title_kh']
        ], null); // learn uses dedicated fullscreen layout
    }

    public function markCompleted(): void {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $userId = Auth::id();
        $courseId = (int)$this->request->input('course_id');
        $lessonId = (int)$this->request->input('lesson_id');
        $completed = (bool)$this->request->input('completed', true);

        $progressModel = new LessonProgress();
        $progressModel->markCompleted($userId, $courseId, $lessonId, $completed);

        $completedIds = $progressModel->getCompletedLessonIds($userId, $courseId);

        $this->json([
            'success'       => true,
            'completed'     => $completed,
            'completedCount'=> count($completedIds)
        ]);
    }

    public function saveProgress(): void {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $userId = Auth::id();
        $courseId = (int)$this->request->input('course_id');
        $lessonId = (int)$this->request->input('lesson_id');
        $seconds = (int)$this->request->input('seconds', 0);

        $progressModel = new LessonProgress();
        $progressModel->saveWatchTime($userId, $courseId, $lessonId, $seconds);

        $this->json(['success' => true]);
    }

    public function profile(): void {
        $user = Auth::user();
        $this->render('student/profile', [
            'user'  => $user,
            'title' => 'ការកំណត់ព័ត៌មានផ្ទាល់ខ្លួន — ក្តឹបកូដ Academy'
        ]);
    }

    public function updateProfile(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $userId = Auth::id();
        $name = trim((string)$this->request->input('name', ''));
        $phone = trim((string)$this->request->input('phone', ''));
        $headline = trim((string)$this->request->input('headline', ''));
        $bio = trim((string)$this->request->input('bio', ''));

        if (empty($name)) {
            $this->withError('ឈ្មោះមិនអាចទទេបានឡើយ');
            $this->redirect('/student/profile');
            return;
        }

        $userModel = new User();
        $updateData = [
            'name'     => $name,
            'phone'    => $phone ?: null,
            'headline' => $headline ?: null,
            'bio'      => $bio ?: null
        ];

        // Avatar upload
        $avatarFile = $this->request->file('avatar');
        if ($avatarFile && !empty($avatarFile['name'])) {
            $uploadRes = Upload::profile($avatarFile);
            if ($uploadRes['success']) {
                $updateData['avatar'] = $uploadRes['path'];
            } else {
                $this->withError($uploadRes['error']);
                $this->redirect('/student/profile');
                return;
            }
        }

        $userModel->update($userId, $updateData);
        $this->withSuccess('ព័ត៌មានផ្ទាល់ខ្លួនត្រូវបានកែប្រែដោយជោគជ័យ!');
        $this->redirect('/student/profile');
    }

    public function updatePassword(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $userId = Auth::id();
        $currentPassword = (string)$this->request->input('current_password', '');
        $newPassword = (string)$this->request->input('new_password', '');
        $confirmPassword = (string)$this->request->input('new_password_confirmation', '');

        $userModel = new User();
        $user = $userModel->findById($userId);

        if (!password_verify($currentPassword, $user['password'])) {
            $this->withError('ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវឡើយ');
            $this->redirect('/student/profile');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->withError('ពាក្យសម្ងាត់ថ្មីត្រូវមានយ៉ាងហោចណាស់ ៦ តួអក្សរ');
            $this->redirect('/student/profile');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $this->withError('ពាក្យសម្ងាត់ថ្មីនិងការបញ្ជាក់ពាក្យសម្ងាត់មិនដូចគ្នាឡើយ');
            $this->redirect('/student/profile');
            return;
        }

        $userModel->updatePassword($userId, $newPassword);
        $this->withSuccess('ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ!');
        $this->redirect('/student/profile');
    }
}
