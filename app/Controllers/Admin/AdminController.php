<?php
namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Helpers\Auth;
use App\Models\AdminActivityLog;

abstract class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        // Ensure user is admin
        if (!Auth::check() || !Auth::isAdmin()) {
            $this->withError('លោកអ្នកគ្មានសិទ្ធិចូលកាន់ផ្ទាំងគ្រប់គ្រង Admin ឡើយ');
            $this->redirect('/login');
            exit;
        }
    }

    protected function renderAdmin(string $viewPath, array $data = []): void {
        $data['adminUser'] = Auth::user();
        view($viewPath, $data, 'layouts/admin');
    }

    protected function logAction(string $action, ?string $targetType = null, ?int $targetId = null, ?string $details = null): void {
        AdminActivityLog::log($action, $targetType, $targetId, $details);
    }
}
