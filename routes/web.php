<?php
/**
 * Application Routes
 * ក្តឹបកូដ Academy
 * 
 * @var App\Core\Router $router
 */

use App\Controllers\HomeController;
use App\Controllers\CourseController;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Controllers\PageController;

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\CourseAdminController;
use App\Controllers\Admin\LessonAdminController;
use App\Controllers\Admin\CategoryAdminController;
use App\Controllers\Admin\StudentAdminController;
use App\Controllers\Admin\OrderAdminController;
use App\Controllers\Admin\CouponAdminController;
use App\Controllers\Admin\ReviewAdminController;
use App\Controllers\Admin\SettingAdminController;
use App\Controllers\Admin\LogAdminController;

use App\Middleware\AuthMiddleware;
use App\Middleware\AdminMiddleware;

// -------------------------------------------------------------
// PUBLIC ROUTES
// -------------------------------------------------------------
$router->get('/', [HomeController::class, 'index']);

// Dynamic Serverless Upload File Streaming
$router->get('/uploads/{folder}/{file}', function ($folder, $file) {
    $safeFolder = preg_replace('/[^a-zA-Z0-9_-]/', '', $folder);
    $safeFile = preg_replace('/[^a-zA-Z0-9_.-]/', '', $file);

    $publicPath = __DIR__ . '/../public/uploads/' . $safeFolder . '/' . $safeFile;
    $tmpPath = '/tmp/uploads/' . $safeFolder . '/' . $safeFile;

    $path = file_exists($publicPath) ? $publicPath : (file_exists($tmpPath) ? $tmpPath : null);

    if (!$path) {
        http_response_code(404);
        die('File not found');
    }

    $mime = mime_content_type($path) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
});

$router->get('/courses', [CourseController::class, 'index']);
$router->get('/course/{slug}', [CourseController::class, 'show']);
$router->post('/course/{id}/wishlist', [CourseController::class, 'toggleWishlist']);
$router->post('/course/{id}/review', [CourseController::class, 'addReview']);

// Cart
$router->get('/cart', [CartController::class, 'index']);
$router->get('/cart/add/{id}', [CartController::class, 'add']);
$router->get('/cart/remove/{id}', [CartController::class, 'remove']);
$router->post('/cart/coupon', [CartController::class, 'applyCoupon']);
$router->get('/cart/coupon/remove', [CartController::class, 'removeCoupon']);

// Info / Static Pages
$router->get('/about', [PageController::class, 'about']);
$router->get('/contact', [PageController::class, 'contact']);
$router->post('/contact', [PageController::class, 'submitContact']);
$router->get('/faq', [PageController::class, 'faq']);
$router->get('/privacy-policy', [PageController::class, 'privacy']);
$router->get('/terms', [PageController::class, 'terms']);

// Authentication
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword']);
$router->post('/forgot-password', [AuthController::class, 'sendResetLink']);
$router->get('/reset-password', [AuthController::class, 'showResetPassword']);
$router->post('/reset-password', [AuthController::class, 'resetPassword']);

// -------------------------------------------------------------
// AUTHENTICATED STUDENT ROUTES
// -------------------------------------------------------------
$router->group(['middleware' => AuthMiddleware::class], function ($r) {
    // Checkout & Orders
    $r->get('/checkout', [OrderController::class, 'checkout']);
    $r->post('/checkout', [OrderController::class, 'processCheckout']);
    $r->get('/order/{id}', [OrderController::class, 'show']);
    $r->post('/order/{id}/receipt', [OrderController::class, 'uploadReceipt']);

    // Student Dashboard & Learning
    $r->get('/student/dashboard', [StudentController::class, 'dashboard']);
    $r->get('/student/my-courses', [StudentController::class, 'myCourses']);
    $r->get('/student/orders', [StudentController::class, 'orders']);
    $r->get('/student/profile', [StudentController::class, 'profile']);
    $r->post('/student/profile', [StudentController::class, 'updateProfile']);
    $r->post('/student/password', [StudentController::class, 'updatePassword']);

    // Interactive Video Player & Learning Progress
    $r->get('/student/learn/{courseSlug}', [StudentController::class, 'learn']);
    $r->get('/student/learn/{courseSlug}/{lessonSlug}', [StudentController::class, 'learn']);
    $r->post('/api/lesson/complete', [StudentController::class, 'markCompleted']);
    $r->post('/api/lesson/progress', [StudentController::class, 'saveProgress']);
});

// -------------------------------------------------------------
// PROTECTED ADMIN ROUTES (/admin/*)
// -------------------------------------------------------------
$router->group(['prefix' => '/admin', 'middleware' => AdminMiddleware::class], function ($r) {
    // Admin Dashboard Overview
    $r->get('', [DashboardController::class, 'index']);
    $r->get('/dashboard', [DashboardController::class, 'index']);

    // Course Management
    $r->get('/courses', [CourseAdminController::class, 'index']);
    $r->get('/courses/create', [CourseAdminController::class, 'create']);
    $r->post('/courses/store', [CourseAdminController::class, 'store']);
    $r->get('/courses/{id}/edit', [CourseAdminController::class, 'edit']);
    $r->post('/courses/{id}/update', [CourseAdminController::class, 'update']);
    $r->post('/courses/{id}/delete', [CourseAdminController::class, 'delete']);

    // Course Sections & Lessons
    $r->get('/courses/{id}/sections', [CourseAdminController::class, 'sections']);
    $r->post('/courses/{id}/sections', [CourseAdminController::class, 'createSection']);
    $r->post('/courses/{courseId}/sections/{sectionId}/delete', [CourseAdminController::class, 'deleteSection']);

    // Dedicated Short Video Lesson Management (/admin/lessons)
    $r->get('/lessons', [LessonAdminController::class, 'index']);
    $r->get('/lessons/create', [LessonAdminController::class, 'create']);
    $r->post('/lessons/store', [LessonAdminController::class, 'store']);
    $r->get('/lessons/{id}/edit', [LessonAdminController::class, 'edit']);
    $r->post('/lessons/{id}/update', [LessonAdminController::class, 'update']);
    $r->post('/lessons/{id}/delete', [LessonAdminController::class, 'delete']);
    $r->post('/lessons/{id}/toggle', [LessonAdminController::class, 'togglePublish']);
    $r->get('/api/courses/{courseId}/sections', [LessonAdminController::class, 'getSections']);

    // Contextual Course Section Lesson Routes (Backwards Compatibility)
    $r->get('/courses/{courseId}/sections/{sectionId}/lessons/create', [LessonAdminController::class, 'create']);
    $r->post('/courses/{courseId}/sections/{sectionId}/lessons/store', [LessonAdminController::class, 'store']);
    $r->get('/courses/{courseId}/lessons/{lessonId}/edit', [LessonAdminController::class, 'edit']);
    $r->post('/courses/{courseId}/lessons/{lessonId}/update', [LessonAdminController::class, 'update']);
    $r->post('/courses/{courseId}/lessons/{lessonId}/delete', [LessonAdminController::class, 'delete']);

    // Category Management
    $r->get('/categories', [CategoryAdminController::class, 'index']);
    $r->post('/categories/store', [CategoryAdminController::class, 'store']);
    $r->post('/categories/{id}/update', [CategoryAdminController::class, 'update']);
    $r->post('/categories/{id}/delete', [CategoryAdminController::class, 'delete']);

    // Student Management
    $r->get('/students', [StudentAdminController::class, 'index']);
    $r->get('/students/{id}', [StudentAdminController::class, 'show']);
    $r->post('/students/{id}/toggle-status', [StudentAdminController::class, 'toggleStatus']);

    // Order & Payment Management
    $r->get('/orders', [OrderAdminController::class, 'index']);
    $r->get('/orders/{id}', [OrderAdminController::class, 'show']);
    $r->post('/orders/{id}/approve', [OrderAdminController::class, 'approvePayment']);
    $r->post('/orders/{id}/reject', [OrderAdminController::class, 'rejectPayment']);

    // Coupons
    $r->get('/coupons', [CouponAdminController::class, 'index']);
    $r->post('/coupons/store', [CouponAdminController::class, 'store']);
    $r->post('/coupons/{id}/delete', [CouponAdminController::class, 'delete']);

    // Review Moderation
    $r->get('/reviews', [ReviewAdminController::class, 'index']);
    $r->post('/reviews/{id}/toggle', [ReviewAdminController::class, 'toggleApproval']);
    $r->post('/reviews/{id}/delete', [ReviewAdminController::class, 'delete']);

    // Website Settings
    $r->get('/settings', [SettingAdminController::class, 'index']);
    $r->post('/settings/general', [SettingAdminController::class, 'updateGeneral']);
    $r->post('/settings/payment-method/{id}', [SettingAdminController::class, 'updatePaymentMethod']);
    $r->post('/settings/faqs', [SettingAdminController::class, 'storeFaq']);
    $r->post('/settings/faqs/{id}/delete', [SettingAdminController::class, 'deleteFaq']);

    // Audit Logs
    $r->get('/logs', [LogAdminController::class, 'index']);
});
