<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <h2 class="fw-bold text-white mb-1"><i class="bi bi-receipt me-2 text-success"></i> ប្រវត្តិបញ្ជាទិញ (Order History)</h2>
        <p class="text-secondary mb-0">ពិនិត្យមើលរាល់ការបញ្ជាទិញ វិក្កយបត្រ និងស្ថានភាពផ្ទៀងផ្ទាត់ការបង់ប្រាក់</p>
    </div>
</div>

<div class="container py-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="bi bi-receipt-cutoff fs-1 text-muted mb-3"></i>
                <h5 class="fw-bold">មិនទាន់មានប្រវត្តិបញ្ជាទិញនៅឡើយទេ</h5>
                <p class="text-muted">រាល់ការបញ្ជាទិញវគ្គសិក្សានឹងត្រូវបង្ហាញនៅទីនេះ</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>លេខវិក្កយបត្រ</th>
                            <th>កាលបរិច្ឆេទ</th>
                            <th>ចំនួនវគ្គ</th>
                            <th>វិធីសាស្ត្រទូទាត់</th>
                            <th>ទឹកប្រាក់សរុប</th>
                            <th>ស្ថានភាព</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td class="fw-bold">
                                    <a href="<?= url('/order/' . $order['id']) ?>" class="text-dark text-decoration-none">
                                        <?= e($order['order_number']) ?>
                                    </a>
                                </td>
                                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td class="small"><?= $order['items_count'] ?> វគ្គសិក្សា</td>
                                <td class="small"><?= e($order['payment_method_name'] ?? 'Manual Transfer') ?></td>
                                <td class="fw-bold text-success">$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= e($order['payment_status']) ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= url('/order/' . $order['id']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        មើលវិក្កយបត្រ
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
