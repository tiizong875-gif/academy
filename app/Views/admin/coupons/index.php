<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងប័ណ្ណបញ្ចុះតម្លៃ (Coupons)</h4>
        <p class="text-muted small mb-0">បង្កើតកូដ Promotion កំណត់ភាគរយបញ្ចុះតម្លៃ និងកាលបរិច្ឆេទផុតកំណត់</p>
    </div>
    <button type="button" class="btn btn-primary-katteb" data-bs-toggle="modal" data-bs-target="#addCouponModal">
        <i class="bi bi-plus-circle me-1"></i> បង្កើតកូដថ្មី
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>កូដ (Code)</th>
                    <th>ប្រភេទ</th>
                    <th>តម្លៃបញ្ចុះ</th>
                    <th>ការបញ្ជាទិញអប្បបរមា</th>
                    <th>កាលបរិច្ឆេទផុតកំណត់</th>
                    <th>ការប្រើប្រាស់</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">មិនទាន់មានកូដបញ្ចុះតម្លៃនៅឡើយទេ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $cp): ?>
                        <tr>
                            <td><strong class="text-success fs-6"><?= e($cp['code']) ?></strong></td>
                            <td class="small"><?= ucfirst($cp['discount_type']) ?></td>
                            <td class="fw-bold small">
                                <?= $cp['discount_type'] === 'percentage' ? number_format($cp['discount_value'], 0) . '%' : '$' . number_format($cp['discount_value'], 2) ?>
                            </td>
                            <td class="small">$<?= number_format($cp['min_order_amount'], 2) ?></td>
                            <td class="small text-muted"><?= $cp['expiry_date'] ? date('d/m/Y', strtotime($cp['expiry_date'])) : 'គ្មានកំណត់' ?></td>
                            <td class="small"><?= $cp['usage_count'] ?> / <?= $cp['usage_limit'] ?: '∞' ?></td>
                            <td>
                                <span class="badge-status badge-<?= $cp['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $cp['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="<?= url('/admin/coupons/' . $cp['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុបកូដនេះមែនទេ?">
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
</div>

<!-- Add Coupon Modal -->
<div class="modal fade" id="addCouponModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">បង្កើតប័ណ្ណបញ្ចុះតម្លៃថ្មី</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/coupons/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">លេខកូដ (Coupon Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" placeholder="ឧ. PROMO2026" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ប្រភេទបញ្ចុះ</label>
                            <select name="discount_type" class="form-select">
                                <option value="percentage">ភាគរយ (%)</option>
                                <option value="fixed">ចំនួនទឹកប្រាក់ ($ USD)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">តម្លៃបញ្ចុះ <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" class="form-control" placeholder="20" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ទិញអប្បបរមា ($)</label>
                            <input type="number" step="0.01" name="min_order_amount" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ចំនួនកំណត់ប្រើ</label>
                            <input type="number" name="usage_limit" class="form-control" value="100">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ថ្ងៃចាប់ផ្ដើម</label>
                            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ថ្ងៃផុតកំណត់</label>
                            <input type="date" name="expiry_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-katteb">បង្កើតកូដឥឡូវនេះ</button>
                </div>
            </form>
        </div>
    </div>
</div>
