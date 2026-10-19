<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងមតិយោបល់ (Reviews Moderation)</h4>
        <p class="text-muted small mb-0">ពិនិត្យ សម្របសម្រួល និងលុបមតិវាយតម្លៃដែលមិនសមរម្យ</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>វគ្គសិក្សា</th>
                    <th>សិស្ស</th>
                    <th>ពិន្ទុ</th>
                    <th>មតិយោបល់</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">មិនមានមតិវាយតម្លៃនៅឡើយទេ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <tr>
                            <td>
                                <a href="<?= url('/course/' . $rev['course_slug']) ?>" target="_blank" class="fw-bold small text-dark text-decoration-none">
                                    <?= e($rev['course_title']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= e($rev['user_name']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= e($rev['user_email']) ?></div>
                            </td>
                            <td>
                                <span class="text-warning small">
                                    <?= str_repeat('<i class="bi bi-star-fill"></i> ', $rev['rating']) ?>
                                </span>
                            </td>
                            <td class="small text-muted" style="max-width: 300px;">
                                <?= nl2br(e($rev['comment'])) ?>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y', strtotime($rev['created_at'])) ?></td>
                            <td>
                                <span class="badge-status badge-<?= $rev['is_approved'] ? 'approved' : 'inactive' ?>">
                                    <?= $rev['is_approved'] ? 'Approved' : 'Hidden' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="<?= url('/admin/reviews/' . $rev['id'] . '/toggle') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-<?= $rev['is_approved'] ? 'warning' : 'success' ?> rounded-circle p-2" title="<?= $rev['is_approved'] ? 'លាក់' : 'បង្ហាញ' ?>">
                                        <i class="bi bi-<?= $rev['is_approved'] ? 'eye-slash' : 'eye' ?>"></i>
                                    </button>
                                </form>

                                <form action="<?= url('/admin/reviews/' . $rev['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុបមតិនេះមែនទេ?">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="លុប">
                                        <i class="bi bi-trash"></i>
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
                        <a class="page-link" href="<?= url('/admin/reviews?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
