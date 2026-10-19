<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <h2 class="fw-bold text-white mb-1"><i class="bi bi-journal-bookmark-fill me-2 text-success"></i> វគ្គសិក្សារបស់ខ្ញុំ (My Enrolled Courses)</h2>
        <p class="text-secondary mb-0">វគ្គសិក្សាទាំងអស់ដែលអ្នកបានចុះឈ្មោះ និងមានសិទ្ធិចូលរៀន</p>
    </div>
</div>

<div class="container py-5">
    <?php if (empty($courses)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <i class="bi bi-collection-play fs-1 text-muted mb-3"></i>
            <h4 class="fw-bold">អ្នកមិនទាន់មានវគ្គសិក្សានៅឡើយទេ</h4>
            <p class="text-muted mb-4">ចាប់ផ្ដើមរៀនជំនាញកូដដែលអ្នកស្រឡាញ់ថ្ងៃនេះ!</p>
            <div>
                <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb px-4">
                    <i class="bi bi-search me-1"></i> រុករកវគ្គសិក្សា
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($courses as $c): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="course-card">
                        <div class="course-thumbnail-wrapper">
                            <img src="<?= uploadUrl('thumbnails/' . $c['thumbnail']) ?>" alt="Course" class="course-thumbnail">
                        </div>

                        <div class="course-card-body">
                            <span class="course-category"><?= e($c['category_name_kh']) ?></span>
                            <h5 class="course-title">
                                <a href="<?= url('/student/learn/' . $c['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= e($c['title_kh']) ?>
                                </a>
                            </h5>

                            <div class="small text-muted mb-3">
                                <i class="bi bi-person me-1"></i> បង្រៀនដោយ៖ <?= e($c['instructor_name']) ?>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>វឌ្ឍនភាពសិក្សា</span>
                                    <span class="fw-bold text-success"><?= $c['progress_percentage'] ?>%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= $c['progress_percentage'] ?>%"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted mt-1" style="font-size: 0.75rem;">
                                    <span>បានចប់ <?= $c['completed_lessons'] ?> / <?= $c['total_lessons'] ?> មេរៀន</span>
                                    <span><?= $c['progress_percentage'] == 100 ? '✅ ចប់រួចរាល់' : 'កំពុងរៀន' ?></span>
                                </div>
                            </div>

                            <a href="<?= url('/student/learn/' . $c['slug']) ?>" class="btn btn-primary-katteb w-100 mt-auto">
                                <i class="bi bi-play-circle-fill me-2"></i> ចូលរៀនឥឡូវនេះ
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
