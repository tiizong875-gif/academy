<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងវគ្គសិក្សា (Courses)</h4>
        <p class="text-muted small mb-0">បង្កើត កែប្រែ រៀបចំជំពូក-មេរៀន និងកំណត់តម្លៃវគ្គសិក្សា</p>
    </div>
    <div>
        <a href="<?= url('/admin/courses/create') ?>" class="btn btn-primary-katteb">
            <i class="bi bi-plus-circle me-1"></i> បង្កើតវគ្គសិក្សាថ្មី
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= url('/admin/courses') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="ស្វែងរកតាមចំណងជើង..." value="<?= e($filters['q']) ?>">
            </div>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">គ្រប់ផ្នែកទាំងអស់ (All Categories)</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($filters['category'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= e($cat['name_kh']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">គ្រប់ស្ថានភាព</option>
                <option value="published" <?= ($filters['status'] === 'published') ? 'selected' : '' ?>>បានផ្សាយ (Published)</option>
                <option value="draft" <?= ($filters['status'] === 'draft') ? 'selected' : '' ?>>ពង្រាង (Draft)</option>
                <option value="archived" <?= ($filters['status'] === 'archived') ? 'selected' : '' ?>>បណ្ណសារ (Archived)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">ច្រោះ (Filter)</button>
        </div>
    </form>
</div>

<!-- Courses Table Card -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>វគ្គសិក្សា</th>
                    <th>ផ្នែកជំនាញ</th>
                    <th>គ្រូបង្រៀន</th>
                    <th>តម្លៃ</th>
                    <th>មេរៀន</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">មិនមានវគ្គសិក្សាត្រូវនឹងលក្ខខណ្ឌស្វែងរកឡើយ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($courses as $c): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= uploadUrl('thumbnails/' . $c['thumbnail']) ?>" class="rounded-2 border" width="70" height="42" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold small">
                                            <a href="<?= url('/course/' . $c['slug']) ?>" target="_blank" class="text-dark text-decoration-none">
                                                <?= e($c['title_kh']) ?>
                                            </a>
                                        </h6>
                                        <span class="small text-muted"><?= e($c['title']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="small fw-semibold"><?= e($c['category_name_kh']) ?></td>
                            <td class="small"><?= e($c['instructor_name']) ?></td>
                            <td>
                                <?php if ($c['is_free']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success">Free</span>
                                <?php elseif ($c['discount_price'] !== null): ?>
                                    <div class="fw-bold text-success small">$<?= number_format($c['discount_price'], 2) ?></div>
                                    <small class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">$<?= number_format($c['price'], 2) ?></small>
                                <?php else: ?>
                                    <span class="fw-bold text-dark small">$<?= number_format($c['price'], 2) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <a href="<?= url('/admin/courses/' . $c['id'] . '/sections') ?>" class="badge bg-light text-dark border text-decoration-none p-2">
                                    <i class="bi bi-list-nested me-1"></i> <?= $c['actual_lessons'] ?> មេរៀន
                                </a>
                            </td>
                            <td>
                                <span class="badge-status badge-<?= e($c['status']) ?>">
                                    <?= ucfirst($c['status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="<?= url('/admin/courses/' . $c['id'] . '/sections') ?>" class="btn btn-sm btn-outline-primary" title="រៀបចំមាតិកា & មេរៀន">
                                        <i class="bi bi-list-task"></i>
                                    </a>
                                    <a href="<?= url('/admin/courses/' . $c['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary" title="កែប្រែ">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= url('/admin/courses/' . $c['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកពិតជាចង់លុបវគ្គសិក្សានេះមែនទេ? មេរៀនទាំងអស់នឹងត្រូវបានលុបជាមួយគ្នា។">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="លុប">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
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
                        <a class="page-link" href="<?= url('/admin/courses?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
