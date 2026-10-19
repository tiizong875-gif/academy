<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin/students') ?>" class="text-secondary">សិស្សទាំងអស់</a></li>
                <li class="breadcrumb-item active text-success"><?= e($student['name']) ?></li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">ព័ត៌មានលម្អិតសិស្ស (Student Profile)</h4>
    </div>
    <a href="<?= url('/admin/students') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Student Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
            <img src="<?= $student['avatar'] ? uploadUrl($student['avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle border border-2 border-success mx-auto mb-3" width="100" height="100" style="object-fit: cover;">
            <h5 class="fw-bold mb-1"><?= e($student['name']) ?></h5>
            <div class="small text-muted mb-3"><?= e($student['headline'] ?? 'សិស្ស') ?></div>

            <div class="d-flex justify-content-center gap-2 mb-4">
                <span class="badge-status badge-<?= e($student['status']) ?>">
                    ស្ថានភាព៖ <?= ucfirst($student['status']) ?>
                </span>
                <span class="badge bg-light text-dark border">
                    Role: <?= ucfirst($student['role']) ?>
                </span>
            </div>

            <div class="text-start border-top pt-3 small text-muted">
                <div class="mb-2"><i class="bi bi-envelope text-success me-2"></i> <?= e($student['email']) ?></div>
                <div class="mb-2"><i class="bi bi-telephone text-success me-2"></i> <?= e($student['phone'] ?? 'មិនបានបញ្ចូល') ?></div>
                <div class="mb-2"><i class="bi bi-calendar text-success me-2"></i> ចុះឈ្មោះ៖ <?= date('d M Y H:i', strtotime($student['created_at'])) ?></div>
                <?php if (!empty($student['bio'])): ?>
                    <div class="mt-3 p-2 bg-light rounded text-dark">
                        <?= nl2br(e($student['bio'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses & Orders -->
    <div class="col-lg-8">
        <!-- Enrolled Courses -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-journal-bookmark-fill text-success me-2"></i> វគ្គសិក្សាដែលបានចុះឈ្មោះ (<?= count($courses) ?>)</h5>

            <?php if (empty($courses)): ?>
                <p class="text-muted small mb-0 py-3 text-center">សិស្សនេះមិនទាន់មានវគ្គសិក្សានៅឡើយទេ។</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($courses as $c): ?>
                        <div class="p-3 border rounded-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= uploadUrl('thumbnails/' . $c['thumbnail']) ?>" class="rounded-2" width="60" height="36" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1 small"><?= e($c['title_kh']) ?></h6>
                                    <div class="small text-muted">
                                        <span>ចប់ <?= $c['completed_lessons'] ?> / <?= $c['total_lessons'] ?> មេរៀន (<?= $c['progress_percentage'] ?>%)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end" style="min-width: 120px;">
                                <div class="progress mb-1" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: <?= $c['progress_percentage'] ?>%"></div>
                                </div>
                                <span class="small fw-bold text-success"><?= $c['progress_percentage'] ?>%</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order History -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-success me-2"></i> ប្រវត្តិបញ្ជាទិញ</h5>

            <?php if (empty($orders)): ?>
                <p class="text-muted small mb-0 py-3 text-center">មិនទាន់មានការបញ្ជាទិញនៅឡើយទេ។</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>លេខវិក្កយបត្រ</th>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>ទឹកប្រាក់</th>
                                <th>ស្ថានភាព</th>
                                <th class="text-end">ពិនិត្យ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td class="fw-bold small"><?= e($o['order_number']) ?></td>
                                    <td class="small text-muted"><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                                    <td class="fw-bold text-success small">$<?= number_format($o['total_amount'], 2) ?></td>
                                    <td>
                                        <span class="badge-status badge-<?= e($o['payment_status']) ?>">
                                            <?= ucfirst($o['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= url('/admin/orders/' . $o['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2 small">
                                            មើល
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
