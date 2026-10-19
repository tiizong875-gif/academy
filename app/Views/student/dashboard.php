<?php
use App\Helpers\Auth;
use App\Helpers\Str;
$user = Auth::user();
?>

<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= $user['avatar'] ? uploadUrl($user['avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle border border-2 border-success" width="60" height="60" style="object-fit: cover;" alt="Avatar">
                <div>
                    <h3 class="fw-bold text-white mb-0">សួស្តី, <?= e($user['name']) ?>! 👋</h3>
                    <p class="text-secondary mb-0 small"><?= e($user['headline'] ?? 'សិស្សកំពុងសិក្សានៅ ក្តឹបកូដ Academy') ?></p>
                </div>
            </div>
            <div>
                <a href="<?= url('/courses') ?>" class="btn btn-outline-success btn-sm px-3 rounded-pill text-white">
                    <i class="bi bi-search me-1"></i> រុករកវគ្គសិក្សាបន្ថែម
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Learning Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                <div class="stat-icon-wrapper icon-blue mx-auto mb-2">
                    <i class="bi bi-journal-check"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalEnrolled ?></h3>
                <span class="text-muted small">វគ្គសិក្សាដែលបានចុះឈ្មោះ</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                <div class="stat-icon-wrapper icon-green mx-auto mb-2">
                    <i class="bi bi-check2-all"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $completedLessonsCount ?></h3>
                <span class="text-muted small">មេរៀនបានរៀនចប់</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                <div class="stat-icon-wrapper icon-purple mx-auto mb-2">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= count($recentOrders) ?></h3>
                <span class="text-muted small">ការបញ្ជាទិញសរុប</span>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                <div class="stat-icon-wrapper icon-amber mx-auto mb-2">
                    <i class="bi bi-award"></i>
                </div>
                <h3 class="fw-bold text-dark mb-0"><?= $totalEnrolled > 0 ? 'សកម្ម' : 'គ្មាន' ?></h3>
                <span class="text-muted small">ស្ថានភាពសិក្សា</span>
            </div>
        </div>
    </div>

    <!-- Continue Learning Section -->
    <?php if ($continueCourse): ?>
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-5 border-start border-4 border-success">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div class="d-flex align-items-center gap-3">
                    <img src="<?= uploadUrl('thumbnails/' . $continueCourse['thumbnail']) ?>" alt="Course" class="rounded-3" width="120" height="70" style="object-fit: cover;">
                    <div>
                        <span class="badge bg-success bg-opacity-10 text-success small mb-1">បន្តការរៀនសូត្រ</span>
                        <h5 class="fw-bold mb-1"><?= e($continueCourse['title_kh']) ?></h5>
                        <div class="small text-muted mb-2">
                            <span>បង្រៀនដោយ៖ <?= e($continueCourse['instructor_name']) ?></span> &bull;
                            <span>រៀនបាន៖ <?= $continueCourse['completed_lessons'] ?> / <?= $continueCourse['total_lessons'] ?> មេរៀន (<?= $continueCourse['progress_percentage'] ?>%)</span>
                        </div>
                        <div class="progress" style="height: 6px; width: 250px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $continueCourse['progress_percentage'] ?>%"></div>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="<?= url('/student/learn/' . $continueCourse['slug']) ?>" class="btn btn-primary-katteb px-4">
                        <i class="bi bi-play-circle-fill me-2"></i> បន្តការរៀនឥឡូវនេះ
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Enrolled Courses Grid -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="bi bi-journal-bookmark-fill text-success me-2"></i> វគ្គសិក្សារបស់ខ្ញុំ</h4>
        <a href="<?= url('/student/my-courses') ?>" class="small text-success fw-bold text-decoration-none">
            មើលទាំងអស់ (<?= $totalEnrolled ?>) <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <?php if (empty($enrolledCourses)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white mb-5">
            <i class="bi bi-collection fs-1 text-muted mb-3"></i>
            <h5 class="fw-bold">អ្នកមិនទាន់បានចុះឈ្មោះក្នុងវគ្គសិក្សាណាមួយនៅឡើយទេ</h5>
            <p class="text-muted">ស្វែងរកវគ្គសិក្សាកូដដើម្បីចាប់ផ្ដើមដំណើរសិក្សារបស់អ្នកថ្ងៃនេះ</p>
            <div class="mt-2">
                <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb px-4">រុករកវគ្គសិក្សា</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4 mb-5">
            <?php foreach (array_slice($enrolledCourses, 0, 3) as $c): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="course-card">
                        <div class="course-thumbnail-wrapper">
                            <img src="<?= uploadUrl('thumbnails/' . $c['thumbnail']) ?>" alt="Course" class="course-thumbnail">
                        </div>
                        <div class="course-card-body">
                            <span class="course-category"><?= e($c['category_name_kh']) ?></span>
                            <h6 class="course-title mb-2"><?= e($c['title_kh']) ?></h6>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>វឌ្ឍនភាពសិក្សា</span>
                                    <span class="fw-bold text-dark"><?= $c['progress_percentage'] ?>%</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: <?= $c['progress_percentage'] ?>%"></div>
                                </div>
                            </div>

                            <a href="<?= url('/student/learn/' . $c['slug']) ?>" class="btn btn-sm btn-primary-katteb w-100 mt-auto">
                                <i class="bi bi-play-circle me-1"></i> ចូលរៀន
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Recent Orders Table -->
    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-receipt text-success me-2"></i> ប្រវត្តិបញ្ជាទិញថ្មីៗ</h5>
            <a href="<?= url('/student/orders') ?>" class="small text-success text-decoration-none">មើលប្រវត្តិទាំងអស់</a>
        </div>

        <?php if (empty($recentOrders)): ?>
            <p class="text-muted small mb-0 py-3 text-center">មិនទាន់មានប្រវត្តិបញ្ជាទិញនៅឡើយទេ។</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>លេខកូដវិក្កយបត្រ</th>
                            <th>កាលបរិច្ឆេទ</th>
                            <th>វិធីសាស្ត្រទូទាត់</th>
                            <th>ចំនួនទឹកប្រាក់</th>
                            <th>ស្ថានភាព</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td class="fw-bold"><?= e($order['order_number']) ?></td>
                                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td class="small"><?= e($order['payment_method_name'] ?? 'Manual Transfer') ?></td>
                                <td class="fw-bold text-success">$<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= e($order['payment_status']) ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?= url('/order/' . $order['id']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        ព័ត៌មានលម្អិត
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
