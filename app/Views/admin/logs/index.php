<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">កំណត់ហេតុសកម្មភាពរដ្ឋបាល (Admin Audit Logs)</h4>
        <p class="text-muted small mb-0">តាមដានរាល់សកម្មភាពសំខាន់ៗដែលបានធ្វើឡើងដោយ Admin នៅក្នុងប្រព័ន្ធ</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>កាលបរិច្ឆេទ & ម៉ោង</th>
                    <th>អ្នកគ្រប់គ្រង (Admin)</th>
                    <th>សកម្មភាព (Action)</th>
                    <th>ប្រភេទទិន្នន័យ (Target)</th>
                    <th>ព័ត៌មានលម្អិត</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">មិនទាន់មានកំណត់ហេតុសកម្មភាពនៅឡើយទេ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="small text-muted"><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                            <td>
                                <strong class="small text-dark"><?= e($log['admin_name'] ?? 'System/Admin') ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-navy text-white small px-2 py-1">
                                    <?= e($log['action']) ?>
                                </span>
                            </td>
                            <td class="small"><?= e($log['target_type'] ?? '-') ?> <?= $log['target_id'] ? '#' . $log['target_id'] : '' ?></td>
                            <td class="small text-muted" style="max-width: 320px;"><?= e($log['details'] ?? '') ?></td>
                            <td><code class="small"><?= e($log['ip_address'] ?? '127.0.0.1') ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="p-3 border-top">
            <ul class="pagination pagination-sm justify-content-end mb-0">
                <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
                    <li class="page-item <?= ($p == $pagination['current_page']) ? 'active' : '' ?>">
                        <a class="page-link" href="<?= url('/admin/logs?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
