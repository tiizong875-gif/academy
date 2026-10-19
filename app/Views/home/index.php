<?php
use App\Helpers\Str;
?>

<!-- Hero Section - Perfectly Centered -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="bi bi-rocket-takeoff-fill"></i> វេទិការៀនកូដឈានមុខគេនៅកម្ពុជា
            </div>
            <h1 class="hero-title">
                រៀនធ្វើ រៀនបង្កើត <br>
                <span class="text-success">រៀនដើម្បីជីវិត</span>
            </h1>
            <p class="hero-subtitle">
                អភិវឌ្ឍជំនាញ Coding & Web Development ជាមួយមេរៀនវីដេអូគុណភាពខ្ពស់ជាភាសាខ្មែរ។ ចាប់ផ្ដើមពីកម្រិតដំបូងរហូតដល់អាចបង្កើតគម្រោងពិតប្រាកដ និងមានការងារធ្វើ។
            </p>
            <div class="hero-actions">
                <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb btn-lg px-4 py-3">
                    <i class="bi bi-collection-play me-2"></i> រុករកវគ្គសិក្សាទាំងអស់
                </a>
                <a href="<?= url('/courses?price=free') ?>" class="btn btn-outline-light btn-lg px-4 py-3">
                    <i class="bi bi-gift me-2"></i> វគ្គសិក្សាឥតគិតថ្លៃ
                </a>
            </div>

            <div class="hero-highlights">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span class="text-light small">មេរៀនជាភាសាខ្មែរ 100%</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span class="text-light small">ចូលរៀនបានពេញមួយជីវិត</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span class="text-light small">អនុវត្តគម្រោងពិតប្រាកដ</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill text-success fs-5"></i>
                    <span class="text-light small"><?= (int)$stats['students'] ?>+ សិស្សកំពុងសិក្សា</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Counter Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['students'] ?>+</div>
                    <div class="stat-label">សិស្សចុះឈ្មោះរៀន</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['courses'] ?>+</div>
                    <div class="stat-label">វគ្គសិក្សាពេញលេញ</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['instructors'] ?>+</div>
                    <div class="stat-label">គ្រូជំនាញបទពិសោធន៍ខ្ពស់</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><?= $stats['satisfaction'] ?></div>
                    <div class="stat-label">កម្រិតពេញចិត្តពីសិស្ស</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold mb-2">ជំនាញកំពុងពេញនិយម</span>
            <h2 class="fw-bold">ស្វែងយល់តាមផ្នែកជំនាញ</h2>
            <p class="text-muted">ជ្រើសរើសផ្នែកដែលអ្នកពេញចិត្ត និងចាប់ផ្ដើមរៀនជំនាញបច្ចេកវិទ្យាសម័យថ្មី</p>
        </div>

        <div class="row g-4">
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg">
                    <a href="<?= url('/courses?category=' . $cat['id']) ?>" class="category-card">
                        <div class="category-icon">
                            <i class="bi <?= e($cat['icon']) ?>"></i>
                        </div>
                        <h6 class="fw-bold mb-1 text-center"><?= e($cat['name_kh']) ?></h6>
                        <span class="small text-muted"><?= $cat['courses_count'] ?> វគ្គសិក្សា</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
            <div>
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold mb-2">ពិសេសប្រចាំខែ</span>
                <h2 class="fw-bold mb-1">វគ្គសិក្សាឆ្នើម (Featured Courses)</h2>
                <p class="text-muted mb-0">វគ្គសិក្សាដែលទទួលបានការគាំទ្រ និងពេញនិយមបំផុតពីសិស្សានុសិស្ស</p>
            </div>
            <a href="<?= url('/courses') ?>" class="btn btn-outline-katteb mt-3 mt-md-0">
                មើលវគ្គសិក្សាទាំងអស់ <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($featuredCourses as $course): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="course-card">
                        <div class="course-thumbnail-wrapper">
                            <img src="<?= uploadUrl('thumbnails/' . $course['thumbnail']) ?>" alt="<?= e($course['title']) ?>" class="course-thumbnail" onerror="this.onerror=null;this.src='<?= asset('images/placeholder-course.jpg') ?>'">
                            <div class="course-badge-overlay">
                                <?php if ($course['is_free']): ?>
                                    <span class="badge-tag badge-free">ឥតគិតថ្លៃ (Free)</span>
                                <?php else: ?>
                                    <span class="badge-tag badge-featured"><i class="bi bi-star-fill me-1"></i> វគ្គឆ្នើម</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="course-card-body">
                            <div class="course-category"><?= e($course['category_name_kh']) ?></div>
                            <h5 class="course-title">
                                <a href="<?= url('/course/' . $course['slug']) ?>" class="text-dark text-decoration-none">
                                    <?= e($course['title_kh']) ?>
                                </a>
                            </h5>

                            <div class="course-meta">
                                <span><i class="bi bi-person-fill"></i> <?= e($course['instructor_name']) ?></span>
                                <span><i class="bi bi-play-btn-fill"></i> <?= $course['total_lessons'] ?> មេរៀន</span>
                                <span><i class="bi bi-clock-fill"></i> <?= Str::durationHours($course['duration_minutes']) ?></span>
                            </div>

                            <div class="d-flex align-items-center gap-1 mb-3 text-warning small">
                                <i class="bi bi-star-fill"></i>
                                <span class="fw-bold text-dark"><?= number_format($course['average_rating'], 1) ?></span>
                                <span class="text-muted">(<?= $course['review_count'] ?> មតិ)</span>
                            </div>

                            <div class="course-price-wrap">
                                <div>
                                    <?php if ($course['is_free']): ?>
                                        <span class="course-current-price text-success">Free</span>
                                    <?php elseif ($course['discount_price'] !== null): ?>
                                        <span class="course-current-price text-success">$<?= number_format($course['discount_price'], 2) ?></span>
                                        <span class="course-old-price">$<?= number_format($course['price'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="course-current-price">$<?= number_format($course['price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= url('/course/' . $course['slug']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    ព័ត៌មានលម្អិត
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose ក្តឹបកូដ Academy -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold mb-2">គុណតម្លៃស្នូល</span>
            <h2 class="fw-bold">ហេតុអ្វីគួរជ្រើសរើស ក្តឹបកូដ Academy?</h2>
            <p class="text-muted">យើងប្តេជ្ញាផ្តល់ជូននូវការអប់រំបច្ចេកវិទ្យាដែលមានគុណភាពខ្ពស់ ជាក់ស្ដែង និងសមរម្យបំផុតសម្រាប់កូនខ្មែរ</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon-wrap">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h5 class="fw-bold mb-2">រៀនអនុវត្តគម្រោងពិត</h5>
                    <p class="text-muted small mb-0">មិនមែនត្រឹមទ្រឹស្តីទេ អ្នកនឹងបានកូដគម្រោងជាក់ស្ដែងដូចជា e-Commerce, Web Apps និង Database ស្មុគស្មាញ។</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon-wrap">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <h5 class="fw-bold mb-2">មេរៀនជាភាសាខ្មែរ</h5>
                    <p class="text-muted small mb-0">ពន្យល់ក្បោះក្បាយ ច្បាស់ៗជាភាសាខ្មែរ ងាយយល់ ទោះបីជាអ្នកចាប់ផ្ដើមពីកម្រិតសូន្យក៏ដោយ។</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon-wrap">
                        <i class="bi bi-infinity"></i>
                    </div>
                    <h5 class="fw-bold mb-2">សិទ្ធិចូលរៀនពេញមួយជីវិត</h5>
                    <p class="text-muted small mb-0">ចុះឈ្មោះម្ដង អាចចូលរៀនបានគ្រប់ពេលវេលា លើគ្រប់ឧបករណ៍ ទាំងកុំព្យូទ័រ និងទូរស័ព្ទដៃ។</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature-box">
                    <div class="feature-icon-wrap">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <h5 class="fw-bold mb-2">គាំទ្រការរៀន 24/7</h5>
                    <p class="text-muted small mb-0">មានសហគមន៍ Telegram និងគ្រូសម្របសម្រួលជួយដោះស្រាយចម្ងល់និងកែកូដនៅពេលជួបបញ្ហា។</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Student Testimonials Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold mb-2">មតិយោបល់</span>
            <h2 class="fw-bold">អ្វីដែលសិស្សានុសិស្សនិយាយអំពីយើង</h2>
            <p class="text-muted">បទពិសោធន៍ពិតៗពីសិស្សដែលបានបញ្ចប់វគ្គសិក្សាជាមួយ ក្តឹបកូដ Academy</p>
        </div>

        <div class="row g-4">
            <?php foreach ($testimonials as $t): ?>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="text-warning mb-3">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="text-muted mb-4 fst-italic">"<?= e($t['content']) ?>"</p>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= e($t['avatar']) ?>" alt="<?= e($t['name']) ?>" class="testimonial-avatar">
                            <div>
                                <h6 class="fw-bold mb-0"><?= e($t['name']) ?></h6>
                                <span class="small text-muted"><?= e($t['role']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA / Newsletter Section -->
<section class="py-5 bg-white text-center">
    <div class="container py-4">
        <div class="bg-dark text-white rounded-4 p-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
            <div class="position-relative" style="z-index: 2;">
                <h2 class="text-white fw-bold mb-3">ត្រៀមខ្លួនរួចរាល់ហើយឬនៅដើម្បីក្លាយជា Developer អាជីព?</h2>
                <p class="text-secondary mb-4 mx-auto" style="max-width: 650px;">
                    ចុះឈ្មោះគណនីថ្ងៃនេះដើម្បីចាប់ផ្ដើមរៀនវគ្គសិក្សាឥតគិតថ្លៃ និងទទួលបានដំណឹងពីវគ្គសិក្សាថ្មីៗមុនគេបង្អស់។
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?= url('/register') ?>" class="btn btn-primary-katteb btn-lg px-4">
                        បង្កើតគណនីឥតគិតថ្លៃឥឡូវនេះ
                    </a>
                    <a href="<?= url('/contact') ?>" class="btn btn-outline-light btn-lg px-4">
                        ទាក់ទងមកយើងខ្ញុំ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
