<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-1">
                        <li class="breadcrumb-item"><a href="<?= url('/student/orders') ?>" class="text-secondary">ប្រវត្តិបញ្ជាទិញ</a></li>
                        <li class="breadcrumb-item active text-success"><?= e($order['order_number']) ?></li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-white mb-0">វិក្កយបត្រ #<?= e($order['order_number']) ?></h2>
            </div>
            <div>
                <span class="badge-status badge-<?= e($order['payment_status']) ?> fs-6 px-4 py-2">
                    <?= ucfirst($order['payment_status']) ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Status Alerts -->
    <?php if ($order['payment_status'] === 'pending'): ?>
        <div class="alert alert-warning border-0 shadow-sm p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-clock-history fs-2 text-warning"></i>
                <div>
                    <h5 class="fw-bold mb-1">ការទូទាត់ប្រាក់កំពុងរង់ចាំការផ្ទៀងផ្ទាត់ (Pending Verification)</h5>
                    <p class="small mb-0 text-muted">
                        ប្រសិនបើអ្នកមិនទាន់បាន Upload បង្កាន់ដៃបង់ប្រាក់ សូមធ្វើការទូទាត់តាម KHQR ខាងក្រោម រួច Upload រូបភាពបង្កាន់ដៃ ដើម្បីឱ្យរដ្ឋបាលផ្ទៀងផ្ទាត់និងបើកសិទ្ធិចូលរៀន។
                    </p>
                </div>
            </div>
        </div>
    <?php elseif ($order['payment_status'] === 'paid'): ?>
        <div class="alert alert-success border-0 shadow-sm p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-patch-check-fill fs-2 text-success"></i>
                <div>
                    <h5 class="fw-bold mb-1">ការទូទាត់ប្រាក់បានជោគជ័យ (Verified & Active)</h5>
                    <p class="small mb-0 text-muted">
                        វិក្កយបត្រនេះត្រូវបានផ្ទៀងផ្ទាត់រួចរាល់ហើយ។ លោកអ្នកអាចចូលរៀនវគ្គសិក្សាទាំងអស់ខាងក្រោមបានភ្លាមៗ!
                    </p>
                </div>
            </div>
        </div>
    <?php elseif ($order['payment_status'] === 'rejected'): ?>
        <div class="alert alert-danger border-0 shadow-sm p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-x-circle-fill fs-2 text-danger"></i>
                <div>
                    <h5 class="fw-bold mb-1">ការទូទាត់ប្រាក់ត្រូវបានបដិសេធ (Payment Rejected)</h5>
                    <p class="small mb-0 text-muted">
                        មូលហេតុ៖ <strong><?= e($order['admin_notes'] ?? 'ព័ត៌មានមិនត្រឹមត្រូវ') ?></strong>។ សូមពិនិត្យការផ្ទេរប្រាក់ឡើងវិញ ឬ Upload បង្កាន់ដៃដែលត្រឹមត្រូវជាថ្មី។
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Order Items & Summary -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-bag-check text-success me-2"></i> វគ្គសិក្សាក្នុងវិក្កយបត្រ</h5>

                <div class="d-flex flex-column gap-3 mb-4">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= $item['thumbnail'] ? uploadUrl('thumbnails/' . $item['thumbnail']) : asset('images/placeholder-course.jpg') ?>" class="rounded-2" width="70" height="42" style="object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <?php if (!empty($item['slug'])): ?>
                                            <a href="<?= url('/course/' . $item['slug']) ?>" class="text-dark text-decoration-none">
                                                <?= e($item['title_kh']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted"><?= e($item['title_kh']) ?></span>
                                        <?php endif; ?>
                                    </h6>
                                    <?php if ($order['payment_status'] === 'paid' && !empty($item['slug'])): ?>
                                        <a href="<?= url('/student/learn/' . $item['slug']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-0">
                                            <i class="bi bi-play-circle me-1"></i> ចូលរៀនឥឡូវនេះ
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="fw-bold text-dark fs-5">
                                <?= $item['price'] == 0 ? 'Free' : '$' . number_format($item['price'], 2) ?>
                            </span>
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
                        <span class="fw-bold">ទឹកប្រាក់សរុប</span>
                        <span class="fw-bold fs-4 text-success">$<?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions & Receipt Upload -->
        <div class="col-lg-5">
            <?php if ($order['payment_status'] !== 'paid'): ?>
                <!-- Bank Info & QR Code -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4 text-center">
                    <h5 class="fw-bold mb-2">វិធីសាស្ត្រទូទាត់ប្រាក់</h5>
                    <p class="text-muted small mb-3"><?= e($order['payment_method_name'] ?? 'Bank Transfer') ?></p>

                    <?php if (!empty($order['qr_image'])): ?>
                        <div class="mb-3">
                            <img src="<?= uploadUrl('qr/' . $order['qr_image']) ?>" alt="QR Code" class="img-fluid rounded-3 border p-2 shadow-sm" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>

                    <div class="p-3 bg-light rounded-3 text-start small mb-3">
                        <div class="mb-1">ឈ្មោះគណនី៖ <strong><?= e($order['account_name'] ?? 'KATTEB CODE ACADEMY') ?></strong></div>
                        <div class="mb-1">លេខគណនី៖ <strong class="text-success fs-6"><?= e($order['account_number'] ?? '001 234 567') ?></strong></div>
                        <div class="text-muted mt-2 border-top pt-2">
                            <i class="bi bi-info-circle text-primary me-1"></i> <?= nl2br(e($order['instructions'] ?? '')) ?>
                        </div>
                    </div>
                </div>

                <!-- Receipt Upload Form -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-2"><i class="bi bi-upload text-success me-2"></i> បញ្ចូលបង្កាន់ដៃបង់ប្រាក់ (Upload Receipt)</h5>
                    <p class="text-muted small mb-3">បន្ទាប់ពីផ្ទេរប្រាក់រួច សូមថតរូបបង្កាន់ដៃ (Screenshot) រួច Upload នៅទីនេះ</p>

                    <form action="<?= url('/order/' . $order['id'] . '/receipt') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">លេខកូដប្រតិបត្តិការ (Transaction ID - ស្រេចចិត្ត)</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="ឧ. ABA-98765432" value="<?= e($order['payment']['transaction_reference'] ?? '') ?>">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">រូបភាពបង្កាន់ដៃ (JPG, PNG, WEBP) <span class="text-danger">*</span></label>
                            <input type="file" name="receipt" class="form-control" accept="image/*" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary-katteb">
                                <i class="bi bi-send-check me-2"></i> ផ្ញើបង្កាន់ដៃដើម្បីផ្ទៀងផ្ទាត់
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Show uploaded receipt if exists -->
            <?php if (!empty($order['payment']['receipt_image'])): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mt-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-image text-primary me-2"></i> បង្កាន់ដៃដែលបាន Upload</h6>
                    <img src="<?= uploadUrl('receipts/' . $order['payment']['receipt_image']) ?>" alt="Receipt" class="img-fluid rounded-3 border">
                    <div class="small text-muted mt-2">
                        <span>ស្ថានភាពផ្ទៀងផ្ទាត់៖ </span>
                        <strong class="text-capitalize text-<?= $order['payment']['status'] === 'approved' ? 'success' : ($order['payment']['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                            <?= e($order['payment']['status']) ?>
                        </strong>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
