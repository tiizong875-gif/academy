<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Helpers\Auth;
use App\Helpers\Session;

class AuthController extends Controller {
    public function showLogin(): void {
        if (Auth::check()) {
            if (Auth::isAdmin()) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/student/dashboard');
            }
            return;
        }

        $this->render('auth/login', [
            'title' => 'ចូលគណនី — ក្តឹបកូដ Academy'
        ]);
    }

    public function login(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $email = trim((string)$this->request->input('email', ''));
        $password = (string)$this->request->input('password', '');

        if (empty($email) || empty($password)) {
            $this->withError('សូមបំពេញអ៊ីមែល និងពាក្យសម្ងាត់');
            $this->withOld(['email' => $email]);
            $this->redirectBack();
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->withError('អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវឡើយ');
            $this->withOld(['email' => $email]);
            $this->redirectBack();
            return;
        }

        if ($user['status'] !== 'active') {
            $this->withError('គណនីរបស់អ្នកត្រូវបានផ្អាកជាបណ្ដោះអាសន្ន។ សូមទាក់ទងមកកាន់រដ្ឋបាល។');
            $this->redirectBack();
            return;
        }

        Auth::login($user);

        if ($user['role'] === 'admin') {
            $this->withSuccess("ស្វាគមន៍ការវិលត្រឡប់មកវិញ លោក {$user['name']}!");
            $this->redirect('/admin');
        } else {
            $this->withSuccess("ស្វាគមន៍ការចូលមកកាន់ ក្តឹបកូដ Academy!");
            $this->redirect('/student/dashboard');
        }
    }

    public function showRegister(): void {
        if (Auth::check()) {
            $this->redirect('/student/dashboard');
            return;
        }

        $this->render('auth/register', [
            'title' => 'ចុះឈ្មោះបង្កើតគណនី — ក្តឹបកូដ Academy'
        ]);
    }

    public function register(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $name = trim((string)$this->request->input('name', ''));
        $email = trim((string)$this->request->input('email', ''));
        $phone = trim((string)$this->request->input('phone', ''));
        $password = (string)$this->request->input('password', '');
        $passwordConfirm = (string)$this->request->input('password_confirmation', '');

        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $this->withError('សូមបំពេញព័ត៌មានដែលចាំបាច់ទាំងអស់');
            $this->withOld(['name' => $name, 'email' => $email, 'phone' => $phone]);
            $this->redirectBack();
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->withError('ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវឡើយ');
            $this->withOld(['name' => $name, 'email' => $email, 'phone' => $phone]);
            $this->redirectBack();
            return;
        }

        if (strlen($password) < 6) {
            $this->withError('ពាក្យសម្ងាត់ត្រូវមានយ៉ាងហោចណាស់ ៦ តួអក្សរ');
            $this->withOld(['name' => $name, 'email' => $email, 'phone' => $phone]);
            $this->redirectBack();
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->withError('ពាក្យសម្ងាត់ផ្ទៀងផ្ទាត់មិនដូចគ្នាទេ');
            $this->withOld(['name' => $name, 'email' => $email, 'phone' => $phone]);
            $this->redirectBack();
            return;
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $this->withError('អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចរាល់ហើយ សូមប្រើអ៊ីមែលផ្សេង');
            $this->withOld(['name' => $name, 'phone' => $phone]);
            $this->redirectBack();
            return;
        }

        $userId = $userModel->register($name, $email, $password, $phone ?: null);
        $newUser = $userModel->find($userId);

        Auth::login($newUser);
        $this->withSuccess('ការចុះឈ្មោះបានជោគជ័យ! សូមស្វាគមន៍មកកាន់ ក្តឹបកូដ Academy');
        $this->redirect('/student/dashboard');
    }

    public function logout(): void {
        Auth::logout();
        $this->withSuccess('អ្នកបានចាកចេញពីគណនីដោយជោគជ័យ');
        $this->redirect('/login');
    }

    public function showForgotPassword(): void {
        $this->render('auth/forgot_password', [
            'title' => 'ភ្លេចពាក្យសម្ងាត់ — ក្តឹបកូដ Academy'
        ]);
    }

    public function sendResetLink(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $email = trim((string)$this->request->input('email', ''));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->withError('សូមបញ្ចូលអ៊ីមែលដែលត្រឹមត្រូវ');
            $this->redirectBack();
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->withError('រកមិនឃើញគណនីដែលមានអ៊ីមែលនេះឡើយ');
            $this->redirectBack();
            return;
        }

        $resetModel = new PasswordReset();
        $token = $resetModel->createToken($email);

        // For local development, display the direct reset link so user can test without SMTP configuration
        $resetUrl = url('/reset-password?token=' . $token);
        Session::flash('reset_debug_link', $resetUrl);
        $this->withSuccess("តំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ឡើងវិញត្រូវបានបង្កើតរួចរាល់! (សូមចុចលើតំណភ្ជាប់ខាងក្រោមដើម្បីបន្ត)");
        $this->redirectBack();
    }

    public function showResetPassword(): void {
        $token = (string)$this->request->input('token', '');
        $resetModel = new PasswordReset();
        $reset = $resetModel->findValidToken($token);

        if (!$reset) {
            $this->withError('តំណភ្ជាប់នេះមិនត្រឹមត្រូវ ឬបានផុតកំណត់ហើយ');
            $this->redirect('/forgot-password');
            return;
        }

        $this->render('auth/reset_password', [
            'token' => $token,
            'email' => $reset['email'],
            'title' => 'កំណត់ពាក្យសម្ងាត់ថ្មី — ក្តឹបកូដ Academy'
        ]);
    }

    public function resetPassword(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $token = (string)$this->request->input('token', '');
        $password = (string)$this->request->input('password', '');
        $passwordConfirm = (string)$this->request->input('password_confirmation', '');

        $resetModel = new PasswordReset();
        $reset = $resetModel->findValidToken($token);

        if (!$reset) {
            $this->withError('តំណភ្ជាប់នេះបានផុតកំណត់ហើយ សូមស្នើសុំម្ដងទៀត');
            $this->redirect('/forgot-password');
            return;
        }

        if (strlen($password) < 6) {
            $this->withError('ពាក្យសម្ងាត់ត្រូវមានយ៉ាងហោចណាស់ ៦ តួអក្សរ');
            $this->redirectBack();
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->withError('ពាក្យសម្ងាត់ផ្ទៀងផ្ទាត់មិនដូចគ្នាទេ');
            $this->redirectBack();
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($reset['email']);
        if ($user) {
            $userModel->updatePassword($user['id'], $password);
            $resetModel->deleteToken($token);
            $this->withSuccess('ពាក្យសម្ងាត់ត្រូវបានផ្លាស់ប្តូរដោយជោគជ័យ! សូមចូលគណនីជាមួយពាក្យសម្ងាត់ថ្មី');
            $this->redirect('/login');
        } else {
            $this->withError('មានបញ្ហាកើតឡើង សូមព្យាយាមម្ដងទៀត');
            $this->redirect('/login');
        }
    }
}
