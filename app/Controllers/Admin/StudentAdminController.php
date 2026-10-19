<?php
namespace App\Controllers\Admin;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Order;

class StudentAdminController extends AdminController {
    public function index(): void {
        $userModel = new User();
        $page = max(1, (int)$this->request->input('page', 1));
        $search = trim((string)$this->request->input('q', ''));

        $studentsData = $userModel->getStudents($page, 15, $search);

        $this->renderAdmin('admin/students/index', [
            'students'   => $studentsData['items'],
            'pagination' => $studentsData,
            'search'     => $search,
            'title'      => 'គ្រប់គ្រងសិស្សានុសិស្ស (Student Management)'
        ]);
    }

    public function show(string $id): void {
        $id = (int)$id;
        $userModel = new User();
        $student = $userModel->find($id);

        if (!$student) {
            $this->render('pages/404');
            return;
        }

        $enrollmentModel = new Enrollment();
        $courses = $enrollmentModel->getUserCourses($id);

        $orderModel = new Order();
        $orders = $orderModel->getUserOrders($id);

        $this->renderAdmin('admin/students/show', [
            'student' => $student,
            'courses' => $courses,
            'orders'  => $orders,
            'title'   => 'ព័ត៌មានលម្អិតសិស្ស៖ ' . $student['name']
        ]);
    }

    public function toggleStatus(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $userModel = new User();
        $student = $userModel->find($id);

        if (!$student) {
            $this->render('pages/404');
            return;
        }

        // Prevent self status toggle
        if ($id === (int)\App\Helpers\Auth::id()) {
            $this->withError('មិនអាចបិទគណនីផ្ទាល់ខ្លួនបានឡើយ');
            $this->redirectBack();
            return;
        }

        $newStatus = $student['status'] === 'active' ? 'inactive' : 'active';
        $userModel->update($id, ['status' => $newStatus]);

        $this->logAction('TOGGLE_USER_STATUS', 'User', $id, "បានប្តូរស្ថានភាពសិស្ស {$student['name']} ទៅជា {$newStatus}");
        $this->withSuccess("បានប្តូរស្ថានភាពសិស្សទៅជា {$newStatus} ដោយជោគជ័យ!");
        $this->redirectBack();
    }
}
