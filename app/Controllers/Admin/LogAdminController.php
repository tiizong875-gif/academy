<?php
namespace App\Controllers\Admin;

use App\Models\AdminActivityLog;

class LogAdminController extends AdminController {
    public function index(): void {
        $logModel = new AdminActivityLog();
        $page = max(1, (int)$this->request->input('page', 1));

        $logsData = $logModel->getLogs($page, 25);

        $this->renderAdmin('admin/logs/index', [
            'logs'       => $logsData['items'],
            'pagination' => $logsData,
            'title'      => 'កំណត់ហេតុសកម្មភាពរដ្ឋបាល (Admin Activity Audit Logs)'
        ]);
    }
}
