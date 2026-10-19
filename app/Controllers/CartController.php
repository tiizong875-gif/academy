<?php
namespace App\Controllers;

use App\Services\CartService;
use App\Models\Course;
use App\Models\Enrollment;
use App\Helpers\Auth;

class CartController extends Controller {
    public function index(): void {
        $cart = CartService::getDetails();
        $this->render('cart/index', [
            'cart'  => $cart,
            'title' => 'កន្ត្រកទំនិញរបស់អ្នក — ក្តឹបកូដ Academy'
        ]);
    }

    public function add(string $courseId): void {
        $courseId = (int)$courseId;
        $courseModel = new Course();
        $course = $courseModel->find($courseId);

        if (!$course || $course['status'] !== 'published') {
            $this->withError('វគ្គសិក្សាមិនមាន ឬត្រូវបានបិទ');
            $this->redirectBack();
            return;
        }

        // Check if student already owns it
        if (Auth::check()) {
            $enrollmentModel = new Enrollment();
            if ($enrollmentModel->isEnrolled(Auth::id(), $courseId)) {
                $this->withWarning('អ្នកបានចុះឈ្មោះក្នុងវគ្គសិក្សានេះរួចរាល់ហើយ!');
                $this->redirect('/student/learn/' . $course['slug']);
                return;
            }
        }

        // If course is free and user is logged in, enroll immediately
        if (($course['is_free'] || $course['price'] == 0) && Auth::check()) {
            $enrollmentModel = new Enrollment();
            $enrollmentModel->enrollUser(Auth::id(), $courseId);
            $this->withSuccess('អ្នកបានចុះឈ្មោះចូលរៀនវគ្គឥតគិតថ្លៃនេះដោយជោគជ័យ!');
            $this->redirect('/student/learn/' . $course['slug']);
            return;
        }

        CartService::add($courseId);
        $this->withSuccess('បានបន្ថែមវគ្គសិក្សាទៅក្នុងកន្ត្រកទំនិញ!');
        $this->redirect('/cart');
    }

    public function remove(string $courseId): void {
        CartService::remove((int)$courseId);
        $this->withSuccess('បានដកវគ្គសិក្សាចេញពីកន្ត្រក');
        $this->redirect('/cart');
    }

    public function applyCoupon(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $code = trim((string)$this->request->input('code', ''));
        if (empty($code)) {
            $this->withError('សូមបញ្ចូលលេខកូដបញ្ចុះតម្លៃ');
            $this->redirect('/cart');
            return;
        }

        $res = CartService::applyCoupon($code);
        if ($res['success']) {
            $this->withSuccess('បានអនុវត្តកូដបញ្ចុះតម្លៃដោយជោគជ័យ!');
        } else {
            $this->withError($res['error']);
        }
        $this->redirect('/cart');
    }

    public function removeCoupon(): void {
        CartService::removeCoupon();
        $this->withSuccess('បានលុបកូដបញ្ចុះតម្លៃចេញ');
        $this->redirect('/cart');
    }
}
