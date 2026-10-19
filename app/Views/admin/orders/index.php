<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងការបញ្ជាទិញ & វិក្កយបត្រ (Orders & Payments)</h4>
        <p class="text-muted small mb-0">ពិនិត្យផ្ទៀងផ្ទាត់បង្កាន់ដៃបង់ប្រាក់ អនុម័ត ឬបដិសេធការបញ្ជាទិញ</p>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form action="<?= url('/admin/orders') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="ស្វែងរកតាមលេខវិក្កយបត្រ ឈ្មោះ ឬអ៊ីមែល..." value="<?= e($search) ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">គ្រប់ស្ថានភាព (All Statuses)</option>
                <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>រង់ចាំផ្ទៀងផ្ទាត់ (Pending)</option>
                <option value="paid" <?= ($status === 'paid') ? 'selected' : '' ?>>បានបង់ប្រាក់ (Paid)</option>
                <option value="rejected" <?= ($status === 'rejected') ? 'selected' : '' ?>>បានបដិសេធ (Rejected)</option>
                <option value="cancelled" <?= ($status === 'cancelled') ? 'selected' : '' ?>>បានលុបចោល (Cancelled)</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">ស្វែងរក</button>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>លេខវិក្កយបត្រ</th>
                    <th>សិស្ស</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>វិធីសាស្ត្រទូទាត់</th>
                    <th>បង្កាន់ដៃ</th>
                    <th>ទឹកប្រាក់</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">មិនមានការបញ្ជាទិញត្រូវនឹងលក្ខខណ្ឌឡើយ</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="fw-bold">
                                <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="text-dark text-decoration-none">
                                    <?= e($order['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= e($order['user_name']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= e($order['user_email']) ?></div>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="small"><?= e($order['payment_method_name'] ?? 'Manual') ?></td>
                            <td>
                                <?php if (!empty($order['receipt_image'])): ?>
                                    <a href="<?= uploadUrl('receipts/' . $order['receipt_image']) ?>" target="_blank" title="ចុចដើម្បីមើលបង្កាន់ដៃ">
                                        <img src="<?= uploadUrl('receipts/' . $order['receipt_image']) ?>" class="rounded-2 border" width="40" height="40" style="object-fit: cover;">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">គ្មាន</span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-success">$<?= number_format($order['total_amount'], 2) ?></td>
                            <td>
                                <span class="badge-status badge-<?= e($order['payment_status']) ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                                    <i class="bi bi-eye me-1"></i> ពិនិត្យ & អនុម័ត
                                </a>
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
                        <a class="page-link" href="<?= url('/admin/orders?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
