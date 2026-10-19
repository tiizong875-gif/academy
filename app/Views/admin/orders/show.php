<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin/orders') ?>" class="text-secondary">ការបញ្ជាទិញទាំងអស់</a></li>
                <li class="breadcrumb-item active text-success"><?= e($order['order_number']) ?></li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">ពិនិត្យផ្ទៀងផ្ទាត់វិក្កយបត្រ #<?= e($order['order_number']) ?></h4>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/orders') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left: Order Details & Receipt -->
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">វគ្គសិក្សាក្នុងវិក្កយបត្រ</h5>
                <span class="badge-status badge-<?= e($order['payment_status']) ?> fs-6 px-3">
                    <?= ucfirst($order['payment_status']) ?>
                </span>
            </div>

            <div class="d-flex flex-column gap-3 mb-4">
                <?php foreach ($order['items'] as $item): ?>
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= $item['thumbnail'] ? uploadUrl('thumbnails/' . $item['thumbnail']) : asset('images/placeholder-course.jpg') ?>" class="rounded-2" width="70" height="42" style="object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0 small text-dark"><?= e($item['title_kh']) ?></h6>
                                <span class="small text-muted"><?= e($item['title']) ?></span>
                            </div>
                        </div>
                        <span class="fw-bold text-success">$<?= number_format($item['price'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="p-3 bg-light rounded-3">
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>តម្លៃសរុប (Subtotal)</span>
                    <span>$<?= number_format($order['subtotal'], 2) ?></span>
                </div>
                <?php if ($order['discount_amount'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 small text-danger">
                        <span>បញ្ចុះតម្លៃ (Coupon <?= e($order['coupon_code'] ?? '') ?>)</span>
                        <span>-$<?= number_format($order['discount_amount'], 2) ?></span>
                    </div>
                <?php endif; ?>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">ទឹកប្រាក់ដែលត្រូវទូទាត់</span>
                    <span class="fw-bold fs-3 text-success">$<?= number_format($order['total_amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Receipt Uploaded by Student -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-image text-primary me-2"></i> បង្កាន់ដៃបង់ប្រាក់ពីសិស្ស (Uploaded Receipt)</h5>

            <?php if (!empty($order['payment']['receipt_image'])): ?>
                <div class="text-center p-3 border rounded-3 bg-light mb-3">
                    <img src="<?= uploadUrl('receipts/' . $order['payment']['receipt_image']) ?>" class="img-fluid rounded-3 shadow-sm border" style="max-height: 450px;" alt="Receipt">
                </div>
                <div class="small text-muted">
                    <div>កាលបរិច្ឆេទ Upload៖ <strong><?= date('d M Y H:i:s', strtotime($order['payment']['created_at'])) ?></strong></div>
                    <div>លេខកូដប្រតិបត្តិការ (Transaction ID)៖ <strong class="text-dark"><?= e($order['payment']['transaction_reference'] ?? 'មិនបានបញ្ចូល') ?></strong></div>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                    <span>សិស្សមិនទាន់បាន Upload បង្កាន់ដៃនៅឡើយទេ</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Student Info & Actions -->
    <div class="col-lg-4">
        <!-- Student Info -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h6 class="fw-bold mb-3 pb-2 border-bottom">ព័ត៌មានសិស្ស</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="<?= asset('images/default-avatar.svg') ?>" class="rounded-circle" width="48" height="48">
                <div>
                    <h6 class="fw-bold mb-0"><?= e($order['user_name']) ?></h6>
                    <span class="small text-muted"><?= e($order['user_email']) ?></span>
                </div>
            </div>
            <div class="small text-muted">
                <div class="mb-1"><i class="bi bi-telephone text-success me-1"></i> លេខទូរស័ព្ទ៖ <strong><?= e($order['user_phone'] ?? '-') ?></strong></div>
                <div><i class="bi bi-calendar text-success me-1"></i> បញ្ជាទិញនៅ៖ <strong><?= date('d M Y H:i', strtotime($order['created_at'])) ?></strong></div>
            </div>
        </div>

        <!-- Verification Actions -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h6 class="fw-bold mb-3 pb-2 border-bottom">ការផ្ទៀងផ្ទាត់ & អនុម័ត</h6>

            <?php if ($order['payment_status'] === 'paid'): ?>
                <div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                    <i class="bi bi-patch-check-fill fs-4 text-success"></i>
                    <div>
                        <strong>បានអនុម័តរួចរាល់</strong>
                        <div class="small"><?= e($order['admin_notes'] ?? 'បានផ្ទៀងផ្ទាត់') ?></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-grid gap-2">
                    <!-- Approve Button -->
                    <form action="<?= url('/admin/orders/' . $order['id'] . '/approve') ?>" method="POST" class="form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់អនុម័តការបង់ប្រាក់នេះ និងបើកសិទ្ធិចូលរៀនជូនសិស្សភ្លាមៗមែនទេ?">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle-fill me-2"></i> អនុម័តការបង់ប្រាក់ (Approve)
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectPaymentModal">
                        <i class="bi bi-x-circle me-1"></i> បដិសេធ (Reject Payment)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold text-danger">បដិសេធការបង់ប្រាក់ (Reject Payment)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/orders/' . $order['id'] . '/reject') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">សូមបញ្ជាក់មូលហេតុនៃការបដិសេធ ដើម្បីឱ្យសិស្សបានដឹង និងកែតម្រូវ៖</p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">មូលហេតុបដិសេធ <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="ឧ. រូបភាពបង្កាន់ដៃមិនច្បាស់ ឬចំនួនទឹកប្រាក់មិនត្រូវនឹងវិក្កយបត្រ..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-danger">បដិសេធការបង់ប្រាក់</button>
                </div>
            </form>
        </div>
    </div>
</div>
