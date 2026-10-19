<div class="row g-4 mb-4">
    <!-- Stat 1: Total Students -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">សិស្សទាំងអស់</div>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalStudents) ?></h3>
                <small class="text-success"><i class="bi bi-person-check-fill me-1"></i> សកម្ម</small>
            </div>
            <div class="stat-icon-wrapper icon-blue">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Courses -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">វគ្គសិក្សា</div>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalCourses) ?></h3>
                <small class="text-muted">បានផ្សាយ</small>
            </div>
            <div class="stat-icon-wrapper icon-green">
                <i class="bi bi-collection-play-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat 3: Total Lessons -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">មេរៀនវីដេអូ</div>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalLessons) ?></h3>
                <small class="text-muted">សរុបទាំងអស់</small>
            </div>
            <div class="stat-icon-wrapper icon-purple">
                <i class="bi bi-play-circle-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat 4: Total Orders -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">ការបញ្ជាទិញ</div>
                <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalOrders) ?></h3>
                <small class="text-muted">Orders</small>
            </div>
            <div class="stat-icon-wrapper icon-amber">
                <i class="bi bi-bag-check-fill"></i>
            </div>
        </div>
    </div>

    <!-- Stat 5: Pending Payments -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">រង់ចាំផ្ទៀងផ្ទាត់</div>
                <h3 class="fw-bold mb-0 text-warning"><?= number_format($pendingPaymentsCount) ?></h3>
                <a href="<?= url('/admin/orders?status=pending') ?>" class="small text-warning text-decoration-none fw-semibold">
                    ពិនិត្យ <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <!-- Stat 6: Total Revenue -->
    <div class="col-sm-6 col-xl-4 col-xxl-2">
        <div class="admin-stat-card">
            <div>
                <div class="text-muted small fw-semibold mb-1">ចំណូលបានផ្ទៀងផ្ទាត់</div>
                <h3 class="fw-bold mb-0 text-success">$<?= number_format($totalRevenue, 2) ?></h3>
                <small class="text-success"><i class="bi bi-patch-check-fill me-1"></i> Paid Orders</small>
            </div>
            <div class="stat-icon-wrapper icon-green">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Monthly Sales Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">តារាងចំណូលប្រចាំខែ (Monthly Revenue)</h5>
                    <p class="text-muted small mb-0">ទិន្នន័យចំណូលជាក់ស្ដែងពីវិក្កយបត្រដែលបានផ្ទៀងផ្ទាត់ក្នុងឆ្នាំ <?= date('Y') ?></p>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                    សរុប៖ $<?= number_format($totalRevenue, 2) ?>
                </span>
            </div>
            <div style="height: 320px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Best Selling Courses -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <h5 class="fw-bold mb-3">វគ្គសិក្សាលក់ដាច់បំផុត</h5>

            <?php if (empty($bestSellers)): ?>
                <p class="text-muted small py-4 text-center">មិនទាន់មានទិន្នន័យលក់នៅឡើយទេ។</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($bestSellers as $bs): ?>
                        <div class="d-flex align-items-center justify-content-between p-2 border rounded-3">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                <img src="<?= uploadUrl('thumbnails/' . $bs['thumbnail']) ?>" class="rounded-2" width="50" height="32" style="object-fit: cover;">
                                <div class="text-truncate">
                                    <h6 class="mb-0 small fw-bold text-truncate" style="max-width: 140px;"><?= e($bs['title']) ?></h6>
                                    <span class="small text-muted"><?= $bs['sales_count'] ?> ការលក់</span>
                                </div>
                            </div>
                            <span class="fw-bold text-success small">$<?= number_format($bs['total_earned'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">ការបញ្ជាទិញថ្មីៗ (Recent Orders)</h5>
                <a href="<?= url('/admin/orders') ?>" class="small text-success text-decoration-none">មើលទាំងអស់</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>លេខវិក្កយបត្រ</th>
                            <th>សិស្ស</th>
                            <th>វិធីសាស្ត្រ</th>
                            <th>ចំនួនទឹកប្រាក់</th>
                            <th>ស្ថានភាព</th>
                            <th class="text-end">ពិនិត្យ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="fw-bold">
                                    <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="text-dark text-decoration-none">
                                        <?= e($order['order_number']) ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold small"><?= e($order['user_name']) ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= e($order['user_email']) ?></div>
                                </td>
                                <td class="small"><?= e($order['payment_method_name'] ?? 'Manual') ?></td>
                                <td class="fw-bold text-success">$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= e($order['payment_status']) ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1">
                                        ពិនិត្យ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Student Signups -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">សិស្សទើបចុះឈ្មោះថ្មី</h5>
                <a href="<?= url('/admin/students') ?>" class="small text-success text-decoration-none">មើលទាំងអស់</a>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php foreach ($recentStudents as $student): ?>
                    <div class="d-flex align-items-center justify-content-between p-2 border rounded-3">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            <img src="<?= $student['avatar'] ? uploadUrl($student['avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle" width="36" height="36" style="object-fit: cover;">
                            <div class="text-truncate">
                                <h6 class="mb-0 small fw-bold text-truncate" style="max-width: 140px;"><?= e($student['name']) ?></h6>
                                <span class="small text-muted" style="font-size: 0.75rem;"><?= e($student['email']) ?></span>
                            </div>
                        </div>
                        <a href="<?= url('/admin/students/' . $student['id']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-2 py-0">
                            មើល
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');
    const salesData = <?= json_encode($monthlySales) ?>;
    const months = ['មករា (Jan)', 'កុម្ភៈ (Feb)', 'មីនា (Mar)', 'មេសា (Apr)', 'ឧសភា (May)', 'មិថុនា (Jun)', 'កក្កដា (Jul)', 'សីហា (Aug)', 'កញ្ញា (Sep)', 'តុលា (Oct)', 'វិច្ឆិកា (Nov)', 'ធ្នូ (Dec)'];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'ចំណូល ($)',
                data: salesData,
                backgroundColor: 'rgba(34, 197, 94, 0.25)',
                borderColor: '#22C55E',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value; }
                    }
                }
            }
        }
    });
});
</script>
