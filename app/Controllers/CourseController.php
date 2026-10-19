<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseSection;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\Wishlist;
use App\Helpers\Auth;
use App\Helpers\Session;

class CourseController extends Controller {
    public function index(): void {
        $courseModel = new Course();
        $categoryModel = new Category();

        $page = max(1, (int)$this->request->input('page', 1));
        $filters = [
            'search'      => trim((string)$this->request->input('q', '')),
            'category_id' => $this->request->input('category'),
            'level'       => $this->request->input('level'),
            'price'       => $this->request->input('price'),
            'sort'        => $this->request->input('sort', 'newest')
        ];

        $coursesData = $courseModel->filterCourses($filters, $page, 9);
        $categories = $categoryModel->getActive();

        $this->render('courses/index', [
            'courses'     => $coursesData['items'],
            'pagination'  => $coursesData,
            'categories'  => $categories,
            'filters'     => $filters,
            'title'       => 'វគ្គសិក្សាទាំងអស់ — ក្តឹបកូដ Academy'
        ]);
    }

    public function show(string $slug): void {
        $courseModel = new Course();
        $sectionModel = new CourseSection();
        $reviewModel = new Review();
        $enrollmentModel = new Enrollment();
        $wishlistModel = new Wishlist();

        $course = $courseModel->findBySlug($slug);
        if (!$course || $course['status'] !== 'published') {
            $this->render('pages/404', ['title' => 'រកមិនឃើញវគ្គសិក្សា']);
            return;
        }

        $sections = $sectionModel->getWithLessons($course['id']);
        $reviews = $reviewModel->getForCourse($course['id']);
        $relatedCourses = $courseModel->getRelated($course['category_id'], $course['id'], 3);

        $isEnrolled = false;
        $isWishlisted = false;
        $hasReviewed = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $isEnrolled = $enrollmentModel->isEnrolled($userId, $course['id']);
            $isWishlisted = $wishlistModel->isInWishlist($userId, $course['id']);
            $hasReviewed = $reviewModel->hasReviewed($userId, $course['id']);
        }

        $this->render('courses/show', [
            'course'         => $course,
            'sections'       => $sections,
            'reviews'        => $reviews,
            'relatedCourses' => $relatedCourses,
            'isEnrolled'     => $isEnrolled,
            'isWishlisted'   => $isWishlisted,
            'hasReviewed'    => $hasReviewed,
            'title'          => $course['title_kh'] . ' — ក្តឹបកូដ Academy'
        ]);
    }

    public function toggleWishlist(string $courseId): void {
        if (!Auth::check()) {
            $this->json(['success' => false, 'message' => 'សូមចូលគណនីជាមុនសិន'], 401);
            return;
        }

        $wishlistModel = new Wishlist();
        $added = $wishlistModel->toggle(Auth::id(), (int)$courseId);

        $this->json([
            'success' => true,
            'added'   => $added,
            'message' => $added ? 'បានរក្សាទុកក្នុងបញ្ជីពេញចិត្ត' : 'បានដកចេញពីបញ្ជីពេញចិត្ត'
        ]);
    }

    public function addReview(string $courseId): void {
        if (!Auth::check()) {
            $this->withError('សូមចូលគណនីជាមុនសិន');
            $this->redirectBack();
            return;
        }

        if (!$this->validateCsrf()) {
            return;
        }

        $userId = Auth::id();
        $courseId = (int)$courseId;
        $enrollmentModel = new Enrollment();

        if (!$enrollmentModel->isEnrolled($userId, $courseId)) {
            $this->withError('មានតែសិស្សដែលបានចុះឈ្មោះក្នុងវគ្គនេះប៉ុណ្ណោះ ទើបអាចដាក់មតិវាយតម្លៃបាន');
            $this->redirectBack();
            return;
        }

        $rating = max(1, min(5, (int)$this->request->input('rating', 5)));
        $comment = trim((string)$this->request->input('comment', ''));

        if (empty($comment)) {
            $this->withError('សូមសរសេរមតិវាយតម្លៃរបស់អ្នក');
            $this->redirectBack();
            return;
        }

        $reviewModel = new Review();
        if ($reviewModel->hasReviewed($userId, $courseId)) {
            $this->withError('អ្នកបានវាយតម្លៃវគ្គសិក្សានេះរួចរាល់ហើយ');
            $this->redirectBack();
            return;
        }

        $reviewModel->create([
            'user_id'     => $userId,
            'course_id'   => $courseId,
            'rating'      => $rating,
            'comment'     => $comment,
            'is_approved' => 1
        ]);

        // Recalculate average rating
        $reviews = $reviewModel->getForCourse($courseId);
        $totalStars = array_sum(array_column($reviews, 'rating'));
        $count = count($reviews);
        $avg = $count > 0 ? round($totalStars / $count, 2) : 5.0;

        $courseModel = new Course();
        $courseModel->update($courseId, [
            'average_rating' => $avg,
            'review_count'   => $count
        ]);

        $this->withSuccess('អរគុណសម្រាប់ការវាយតម្លៃរបស់អ្នក!');
        $this->redirectBack();
    }
}
