<?php
namespace App\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Enrollment;

class DashboardController extends AdminController {
    public function index(): void {
        $userModel = new User();
        $courseModel = new Course();
        $lessonModel = new Lesson();
        $orderModel = new Order();
        $db = $orderModel->getDb();

        // 1. Statistics Cards
        $totalStudents = $userModel->count("role = 'student'");
        $totalCourses = $courseModel->count();
        $totalLessons = $lessonModel->count();
        $totalOrders = $orderModel->count();

        // Revenue from verified payments
        $revStmt = $db->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE payment_status = 'paid'");
        $totalRevenue = (float)$revStmt->fetchColumn();

        // Pending payments count
        $pendingPaymentsCount = $orderModel->count("payment_status = 'pending'");

        // 2. Recent Orders
        $recentOrdersSql = "SELECT o.*, u.name as user_name, u.email as user_email, pm.name as payment_method_name
                            FROM orders o
                            JOIN users u ON o.user_id = u.id
                            LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                            ORDER BY o.id DESC LIMIT 6";
        $recentOrders = $db->query($recentOrdersSql)->fetchAll();

        // 3. Recent Students
        $recentStudents = $userModel->where("role", "student", "id DESC LIMIT 5");

        // 4. Best Selling Courses
        $bestSellersSql = "SELECT c.id, c.title, c.title_kh, c.price, c.thumbnail, c.slug,
                                  COUNT(oi.id) as sales_count,
                                  COALESCE(SUM(oi.price), 0) as total_earned
                           FROM courses c
                           JOIN order_items oi ON c.id = oi.course_id
                           JOIN orders o ON oi.order_id = o.id
                           WHERE o.payment_status = 'paid'
                           GROUP BY c.id
                           ORDER BY sales_count DESC LIMIT 5";
        $bestSellers = $db->query($bestSellersSql)->fetchAll();

        // 5. Monthly sales chart data for current year
        $monthlySales = array_fill(1, 12, 0.0);
        $chartSql = "SELECT MONTH(created_at) as month, SUM(total_amount) as total
                     FROM orders
                     WHERE payment_status = 'paid' AND YEAR(created_at) = YEAR(CURRENT_DATE())
                     GROUP BY MONTH(created_at)";
        $chartRows = $db->query($chartSql)->fetchAll();
        foreach ($chartRows as $row) {
            $monthlySales[(int)$row['month']] = (float)$row['total'];
        }

        $this->renderAdmin('admin/dashboard/index', [
            'totalStudents'        => $totalStudents,
            'totalCourses'         => $totalCourses,
            'totalLessons'         => $totalLessons,
            'totalOrders'          => $totalOrders,
            'totalRevenue'         => $totalRevenue,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'recentOrders'         => $recentOrders,
            'recentStudents'       => $recentStudents,
            'bestSellers'          => $bestSellers,
            'monthlySales'         => array_values($monthlySales),
            'title'                => 'ផ្ទាំងគ្រប់គ្រងទូទៅ (Dashboard Overview)'
        ]);
    }
}
