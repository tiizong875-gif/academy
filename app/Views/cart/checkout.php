<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <h2 class="fw-bold text-white mb-1"><i class="bi bi-shield-check me-2 text-success"></i> ការទូទាត់ប្រាក់ (Checkout)</h2>
        <p class="text-secondary mb-0">ជ្រើសរើសវិធីសាស្ត្រទូទាត់ប្រាក់ និងបញ្ជាក់ការបញ្ជាទិញរបស់អ្នក</p>
    </div>
</div>

<div class="container py-5">
    <form action="<?= url('/checkout') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Side: Payment Method Options -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front text-success me-2"></i> ជ្រើសរើសវិធីសាស្ត្រទូទាត់ប្រាក់ (Payment Method)</h5>
                    
                    <?php if ($cart['total'] == 0): ?>
                        <div class="alert alert-success d-flex align-items-center gap-3 p-3">
                            <i class="bi bi-gift-fill fs-2 text-success"></i>
                            <div>
                                <h6 class="fw-bold mb-1">វគ្គសិក្សាឥតគិតថ្លៃ (100% Free)</h6>
                                <p class="small mb-0 text-muted">ការបញ្ជាទិញនេះមិនគិតថ្លៃឡើយ។ អ្នកអាចចុចបញ្ជាក់ការបញ្ជាទិញដើម្បីចាប់ផ្ដើមរៀនភ្លាមៗ!</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-3 mb-4">
                            <?php foreach ($paymentMethods as $index => $pm): ?>
                                <label class="border rounded-3 p-3 d-flex align-items-start gap-3 cursor-pointer payment-method-card <?= $index === 0 ? 'border-success bg-light' : '' ?>" style="cursor: pointer;">
                                    <input type="radio" name="payment_method_id" value="<?= $pm['id'] ?>" class="form-check-input mt-1" <?= $index === 0 ? 'checked' : '' ?> required>
                                    <?php if (!empty($pm['qr_image'])): ?>
                                        <img src="<?= uploadUrl('qr/' . $pm['qr_image']) ?>" alt="QR" class="border rounded p-1 bg-white" width="60" height="60" style="object-fit: contain;">
                                    <?php endif; ?>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold text-dark"><?= e($pm['name']) ?></span>
                                            <span class="badge bg-success bg-opacity-10 text-success">KHQR Manual</span>
                                        </div>
                                        <div class="small text-muted mb-2">
                                            ឈ្មោះគណនី៖ <strong><?= e($pm['account_name']) ?></strong> &bull; លេខគណនី៖ <strong class="text-dark"><?= e($pm['account_number']) ?></strong>
                                        </div>
                                        <div class="small text-secondary bg-white p-2 rounded border">
                                            <i class="bi bi-info-circle me-1 text-primary"></i> <?= nl2br(e($pm['instructions'])) ?>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">កំណត់សម្គាល់បន្ថែម (Notes - ស្រេចចិត្ត)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="ព័ត៌មានបន្ថែម ឬឈ្មោះអ្នកផ្ទេរ..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-3">សង្ខេបបញ្ជីទំនិញ</h5>

                    <div class="d-flex flex-column gap-3 mb-4 max-h-300 overflow-y-auto">
                        <?php foreach ($cart['items'] as $item): ?>
                            <div class="d-flex justify-content-between align-items-center gap-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= uploadUrl('thumbnails/' . $item['thumbnail']) ?>" class="rounded-2" width="50" height="32" style="object-fit: cover;">
                                    <span class="small fw-semibold text-dark text-truncate" style="max-width: 220px;">
                                        <?= e($item['title_kh']) ?>
                                    </span>
                                </div>
                                <span class="fw-bold small">
                                    <?= $item['effective_price'] == 0 ? 'Free' : '$' . number_format($item['effective_price'], 2) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>តម្លៃសរុប (Subtotal)</span>
                        <span>$<?= number_format($cart['subtotal'], 2) ?></span>
                    </div>

                    <?php if ($cart['discount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 small text-danger">
                            <span>បញ្ចុះតម្លៃ (Coupon <?= e($cart['coupon']['code'] ?? '') ?>)</span>
                            <span>-$<?= number_format($cart['discount'], 2) ?></span>
                        </div>
                    <?php endif; ?>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5">សរុបត្រូវបង់</span>
                        <span class="fw-bold fs-2 text-success">$<?= number_format($cart['total'], 2) ?></span>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-katteb btn-lg">
                            <i class="bi bi-shield-check me-2"></i> បញ្ជាក់ការបញ្ជាទិញ
                        </button>
                    </div>

                    <div class="text-center small text-muted">
                        <i class="bi bi-lock-fill text-success me-1"></i> ការទូទាត់ប្រាក់មានសុវត្ថិភាព 100%
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
