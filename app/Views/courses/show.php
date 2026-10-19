<?php
use App\Helpers\Str;
use App\Helpers\Auth;
?>

<!-- Course Header Banner -->
<div class="bg-navy py-5 text-white" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-3">
        <div class="row g-4 align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small mb-3">
                        <li class="breadcrumb-item"><a href="<?= url('/') ?>" class="text-secondary">ទំព័រដើម</a></li>
                        <li class="breadcrumb-item"><a href="<?= url('/courses') ?>" class="text-secondary">វគ្គសិក្សា</a></li>
                        <li class="breadcrumb-item active text-success"><?= e($course['category_name_kh']) ?></li>
                    </ol>
                </nav>

                <h1 class="fw-bold text-white mb-3"><?= e($course['title_kh']) ?></h1>
                <p class="text-light text-opacity-75 fs-5 mb-4"><?= e($course['short_description']) ?></p>

                <div class="d-flex flex-wrap align-items-center gap-4 text-secondary small">
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?= $course['instructor_avatar'] ? uploadUrl($course['instructor_avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle" width="32" height="32" alt="Instructor">
                        <span class="text-white">បង្រៀនដោយ <strong><?= e($course['instructor_name']) ?></strong></span>
                    </div>
                    <div><i class="bi bi-bar-chart-fill text-success me-1"></i> កម្រិត៖ <span class="text-white"><?= ucfirst($course['level']) ?></span></div>
                    <div><i class="bi bi-translate text-success me-1"></i> ភាសា៖ <span class="text-white"><?= e($course['language']) ?></span></div>
                    <div class="d-flex align-items-center text-warning">
                        <i class="bi bi-star-fill me-1"></i>
                        <span class="fw-bold text-white"><?= number_format($course['average_rating'], 1) ?></span>
                        <span class="text-secondary ms-1">(<?= $course['review_count'] ?> មតិវាយតម្លៃ)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Learning Objectives Box -->
            <?php if (!empty($course['learning_objectives'])): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white border-start border-4 border-success">
                    <h5 class="fw-bold mb-3"><i class="bi bi-check2-circle text-success me-2"></i> អ្វីដែលអ្នកនឹងទទួលបានពីវគ្គនេះ</h5>
                    <div class="row g-2">
                        <?php 
                        $objectives = explode("\n", trim($course['learning_objectives']));
                        foreach ($objectives as $obj): 
                            if (trim($obj)):
                        ?>
                            <div class="col-md-6 d-flex align-items-start gap-2">
                                <i class="bi bi-check-lg text-success mt-1"></i>
                                <span><?= e(trim($obj)) ?></span>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Course Curriculum Accordion -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0">មាតិកាវគ្គសិក្សា (Curriculum)</h4>
                    <span class="text-muted small">
                        <?= count($sections) ?> ជំពូក &bull; <?= $course['total_lessons'] ?> មេរៀន &bull; <?= Str::durationHours($course['duration_minutes']) ?>
                    </span>
                </div>

                <div class="accordion curriculum-accordion" id="curriculumAccordion">
                    <?php foreach ($sections as $sIndex => $sec): ?>
                        <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingSec<?= $sec['id'] ?>">
                                <button class="accordion-button <?= $sIndex > 0 ? 'collapsed' : '' ?> bg-light fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSec<?= $sec['id'] ?>">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <span><?= e($sec['title']) ?></span>
                                        <span class="badge bg-secondary bg-opacity-25 text-dark rounded-pill small"><?= count($sec['lessons']) ?> មេរៀន</span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapseSec<?= $sec['id'] ?>" class="accordion-collapse collapse <?= $sIndex === 0 ? 'show' : '' ?>" data-bs-parent="#curriculumAccordion">
                                <div class="accordion-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($sec['lessons'] as $les): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <i class="bi <?= $isEnrolled || $les['is_free_preview'] ? 'bi-play-circle-fill text-success' : 'bi-lock-fill text-muted' ?> fs-5"></i>
                                                    <div>
                                                        <div class="fw-medium text-dark"><?= e($les['title']) ?></div>
                                                        <?php if ($les['duration_seconds'] > 0): ?>
                                                            <small class="text-muted"><?= Str::duration($les['duration_seconds']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div>
                                                    <?php if ($les['is_free_preview']): ?>
                                                        <a href="<?= url('/student/learn/' . $course['slug'] . '/' . $les['slug']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                            <i class="bi bi-eye me-1"></i> សាកល្បង Free
                                                        </a>
                                                    <?php elseif ($isEnrolled): ?>
                                                        <a href="<?= url('/student/learn/' . $course['slug'] . '/' . $les['slug']) ?>" class="btn btn-sm btn-success rounded-pill px-3">
                                                            ចូលរៀន
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border"><i class="bi bi-lock me-1"></i> ចាក់សោ</span>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Course Description -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="fw-bold mb-3">ការពិពណ៌នាពេញលេញ</h4>
                <div class="course-rich-description">
                    <?= $course['description'] ?>
                </div>
            </div>

            <!-- Requirements & Target Audience -->
            <div class="row g-4 mb-4">
                <?php if (!empty($course['requirements'])): ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                            <h5 class="fw-bold mb-3"><i class="bi bi-exclamation-circle text-primary me-2"></i> លក្ខខណ្ឌតម្រូវ (Requirements)</h5>
                            <ul class="mb-0 text-muted ps-3">
                                <?php foreach (explode("\n", trim($course['requirements'])) as $req): ?>
                                    <?php if (trim($req)): ?><li><?= e(trim($req)) ?></li><?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($course['target_audience'])): ?>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                            <h5 class="fw-bold mb-3"><i class="bi bi-people text-info me-2"></i> សម្រាប់អ្នកណាខ្លះ?</h5>
                            <ul class="mb-0 text-muted ps-3">
                                <?php foreach (explode("\n", trim($course['target_audience'])) as $aud): ?>
                                    <?php if (trim($aud)): ?><li><?= e(trim($aud)) ?></li><?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Instructor Profile Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="fw-bold mb-3">អំពីគ្រូបង្រៀន</h4>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-4">
                    <img src="<?= $course['instructor_avatar'] ? uploadUrl($course['instructor_avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle shadow-sm" width="90" height="90" style="object-fit: cover;" alt="Instructor">
                    <div>
                        <h5 class="fw-bold mb-1"><?= e($course['instructor_name']) ?></h5>
                        <div class="text-success small fw-semibold mb-2"><?= e($course['instructor_headline'] ?? 'Senior Web Developer') ?></div>
                        <p class="text-muted small mb-0"><?= nl2br(e($course['instructor_bio'] ?? '')) ?></p>
                    </div>
                </div>
            </div>

            <!-- Student Reviews Section -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">មតិវាយតម្លៃពីសិស្ស (<?= count($reviews) ?>)</h4>
                    <div class="text-warning">
                        <i class="bi bi-star-fill"></i> <strong class="text-dark fs-5"><?= number_format($course['average_rating'], 1) ?></strong> / 5.0
                    </div>
                </div>

                <!-- Review Form for Enrolled Students -->
                <?php if ($isEnrolled && !$hasReviewed): ?>
                    <div class="p-4 bg-light rounded-3 mb-4">
                        <h6 class="fw-bold mb-3">ផ្តល់មតិវាយតម្លៃសម្រាប់វគ្គសិក្សានេះ</h6>
                        <form action="<?= url('/course/' . $course['id'] . '/review') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">ពិន្ទុផ្កាយ (Rating)</label>
                                <select name="rating" class="form-select w-auto">
                                    <option value="5">⭐⭐⭐⭐⭐ (៥ ផ្កាយ - ល្អឥតខ្ចោះ)</option>
                                    <option value="4">⭐⭐⭐⭐ (៤ ផ្កាយ - ល្អណាស់)</option>
                                    <option value="3">⭐⭐⭐ (៣ ផ្កាយ - មធ្យម)</option>
                                    <option value="2">⭐⭐ (២ ផ្កាយ)</option>
                                    <option value="1">⭐ (១ ផ្កាយ)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">មតិរបស់អ្នក</label>
                                <textarea name="comment" class="form-control" rows="3" placeholder="ចែករំលែកអារម្មណ៍របស់អ្នកចំពោះវគ្គសិក្សានេះ..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary-katteb px-4">ផ្ញើមតិវាយតម្លៃ</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if (empty($reviews)): ?>
                    <p class="text-muted text-center py-3">មិនទាន់មានមតិវាយតម្លៃនៅឡើយទេ។</p>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($reviews as $rev): ?>
                            <div class="p-3 border rounded-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= $rev['user_avatar'] ? uploadUrl($rev['user_avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle" width="36" height="36" alt="User">
                                        <div>
                                            <div class="fw-bold small"><?= e($rev['user_name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($rev['created_at'])) ?></div>
                                        </div>
                                    </div>
                                    <div class="text-warning small">
                                        <?= str_repeat('<i class="bi bi-star-fill"></i> ', $rev['rating']) ?>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0"><?= nl2br(e($rev['comment'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Card (Purchase Box) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top bg-white" style="top: 90px;">
                <!-- Video Preview / Thumbnail -->
                <div class="position-relative rounded-3 overflow-hidden mb-3">
                    <img src="<?= uploadUrl('thumbnails/' . $course['thumbnail']) ?>" alt="Course" class="img-fluid w-100" onerror="this.onerror=null;this.src='<?= asset('images/placeholder-course.jpg') ?>'">
                    <?php if (!empty($course['video_preview_url'])): ?>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <a href="<?= e($course['video_preview_url']) ?>" target="_blank" class="btn btn-danger rounded-circle p-3 shadow-lg">
                                <i class="bi bi-play-fill fs-3"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Price Area -->
                <div class="mb-4">
                    <?php if ($course['is_free']): ?>
                        <div class="fs-2 fw-bold text-success mb-1">ឥតគិតថ្លៃ (Free)</div>
                    <?php elseif ($course['discount_price'] !== null): ?>
                        <div class="d-flex align-items-baseline gap-2 mb-1">
                            <span class="fs-2 fw-bold text-success">$<?= number_format($course['discount_price'], 2) ?></span>
                            <span class="fs-5 text-muted text-decoration-line-through">$<?= number_format($course['price'], 2) ?></span>
                            <span class="badge bg-danger rounded-pill">បញ្ចុះតម្លៃ <?= round((($course['price'] - $course['discount_price']) / $course['price']) * 100) ?>%</span>
                        </div>
                    <?php else: ?>
                        <div class="fs-2 fw-bold text-dark mb-1">$<?= number_format($course['price'], 2) ?></div>
                    <?php endif; ?>
                    <small class="text-muted">ចូលរៀនបានពេញមួយជីវិត និងទទួលបាន Update ឥតគិតថ្លៃ</small>
                </div>

                <!-- Action Button Area -->
                <div class="d-grid gap-2 mb-4">
                    <?php if ($isEnrolled): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-2 py-2">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <span>អ្នកបានចុះឈ្មោះក្នុងវគ្គនេះរួចហើយ!</span>
                        </div>
                        <a href="<?= url('/student/learn/' . $course['slug']) ?>" class="btn btn-primary-katteb btn-lg">
                            <i class="bi bi-play-circle me-2"></i> បន្តការរៀនសូត្រ
                        </a>
                    <?php elseif ($course['is_free']): ?>
                        <a href="<?= url('/cart/add/' . $course['id']) ?>" class="btn btn-primary-katteb btn-lg">
                            <i class="bi bi-check-circle me-2"></i> ចុះឈ្មោះរៀន Free ឥឡូវនេះ
                        </a>
                    <?php else: ?>
                        <a href="<?= url('/cart/add/' . $course['id']) ?>" class="btn btn-primary-katteb btn-lg">
                            <i class="bi bi-cart-plus me-2"></i> បន្ថែមទៅកន្ត្រកទំនិញ
                        </a>
                    <?php endif; ?>

                    <!-- Wishlist Button -->
                    <button class="btn btn-outline-secondary rounded-3 py-2 btn-wishlist-toggle" data-course-id="<?= $course['id'] ?>">
                        <i class="bi <?= $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' ?> me-2"></i> បញ្ជីពេញចិត្ត
                    </button>
                </div>

                <!-- Course Features List -->
                <h6 class="fw-bold mb-3">វគ្គសិក្សានេះរួមមាន៖</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 text-muted small mb-0">
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-camera-video text-success"></i> <?= Str::durationHours($course['duration_minutes']) ?> នៃវីដេអូបង្រៀនគុណភាពខ្ពស់
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text text-success"></i> <?= $course['total_lessons'] ?> មេរៀនអនុវត្តជាក់ស្ដែង
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-phone text-success"></i> អាចរៀនលើទូរស័ព្ទដៃ និងកុំព្យូទ័រ
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-infinity text-success"></i> សិទ្ធិចូលរៀនរហូតមិនកំណត់ពេល
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-success"></i> ធានាការគាំទ្រ និងដោះស្រាយចម្ងល់
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
