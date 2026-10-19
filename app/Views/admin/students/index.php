<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងសិស្សានុសិស្ស (Student Management)</h4>
        <p class="text-muted small mb-0">ពិនិត្យមើលបញ្ជីសិស្ស វគ្គសិក្សាដែលបានចុះឈ្មោះ និងស្ថានភាពគណនី</p>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= url('/admin/students') ?>" method="GET" class="row g-2">
        <div class="col-md-10">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="ស្វែងរកតាមឈ្មោះ អ៊ីមែល ឬលេខទូរស័ព្ទ..." value="<?= e($search) ?>">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">ស្វែងរក</button>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>សិស្ស</th>
                    <th>អ៊ីមែល</th>
                    <th>លេខទូរស័ព្ទ</th>
                    <th>កាលបរិច្ឆេទចុះឈ្មោះ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">មិនមានសិស្សត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= $s['avatar'] ? uploadUrl($s['avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold small text-dark"><?= e($s['name']) ?></h6>
                                        <span class="small text-muted" style="font-size: 0.75rem;"><?= e($s['headline'] ?? 'សិស្ស') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="small"><?= e($s['email']) ?></td>
                            <td class="small"><?= e($s['phone'] ?? '-') ?></td>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                            <td>
                                <span class="badge-status badge-<?= e($s['status']) ?>">
                                    <?= ucfirst($s['status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= url('/admin/students/' . $s['id']) ?>" class="btn btn-sm btn-outline-primary rounded-circle p-2" title="មើលព័ត៌មានលម្អិត">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <form action="<?= url('/admin/students/' . $s['id'] . '/toggle-status') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់ប្តូរស្ថានភាពគណនីសិស្សនេះមែនទេ?">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-<?= $s['status'] === 'active' ? 'warning' : 'success' ?> rounded-circle p-2" title="<?= $s['status'] === 'active' ? 'ផ្អាកគណនី' : 'បើកដំណើរការវិញ' ?>">
                                        <i class="bi bi-<?= $s['status'] === 'active' ? 'pause-fill' : 'play-fill' ?>"></i>
                                    </button>
                                </form>
                            </td>
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
                        <a class="page-link" href="<?= url('/admin/students?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
