<?php
use App\Helpers\Str;
?>

<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-3">
        <h1 class="fw-bold text-white mb-2">វគ្គសិក្សាទាំងអស់ (All Courses)</h1>
        <p class="text-secondary mb-0">ស្វែងរកវគ្គសិក្សាកូដ និងបច្ចេកវិទ្យាដែលស័ក្តិសមសម្រាប់កម្រិតជំនាញរបស់អ្នក</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2 text-success"></i> តម្រងស្វែងរក (Filters)</h5>
                
                <form action="<?= url('/courses') ?>" method="GET">
                    <!-- Search Input -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">ស្វែងរកតាមឈ្មោះ</label>
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="វាយពាក្យគន្លឹះ..." value="<?= e($filters['search']) ?>">
                            <button class="btn btn-success" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">ផ្នែកជំនាញ (Category)</label>
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">គ្រប់ផ្នែកទាំងអស់</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name_kh']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Difficulty Level -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">កម្រិតសិក្សា (Level)</label>
                        <select name="level" class="form-select" onchange="this.form.submit()">
                            <option value="">គ្រប់កម្រិតទាំងអស់</option>
                            <option value="beginner" <?= ($filters['level'] === 'beginner') ? 'selected' : '' ?>>កម្រិតដំបូង (Beginner)</option>
                            <option value="intermediate" <?= ($filters['level'] === 'intermediate') ? 'selected' : '' ?>>កម្រិតមធ្យម (Intermediate)</option>
                            <option value="advanced" <?= ($filters['level'] === 'advanced') ? 'selected' : '' ?>>កម្រិតខ្ពស់ (Advanced)</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">តម្លៃ (Price)</label>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="price" id="price_all" value="" <?= empty($filters['price']) ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_all">ទាំងអស់</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="radio" name="price" id="price_free" value="free" <?= ($filters['price'] === 'free') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_free">ឥតគិតថ្លៃ (Free)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="price" id="price_paid" value="paid" <?= ($filters['price'] === 'paid') ? 'checked' : '' ?> onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_paid">បង់ប្រាក់ (Paid)</label>
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold">តម្រៀបតាម (Sort By)</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="newest" <?= ($filters['sort'] === 'newest') ? 'selected' : '' ?>>ថ្មីបំផុត</option>
                            <option value="popular" <?= ($filters['sort'] === 'popular') ? 'selected' : '' ?>>ពេញនិយមបំផុត</option>
                            <option value="rating" <?= ($filters['sort'] === 'rating') ? 'selected' : '' ?>>ពិន្ទុខ្ពស់បំផុត</option>
                            <option value="price_low" <?= ($filters['sort'] === 'price_low') ? 'selected' : '' ?>>តម្លៃទាបទៅខ្ពស់</option>
                            <option value="price_high" <?= ($filters['sort'] === 'price_high') ? 'selected' : '' ?>>តម្លៃខ្ពស់ទៅទាប</option>
                        </select>
                    </div>

                    <a href="<?= url('/courses') ?>" class="btn btn-outline-secondary w-100 rounded-pill btn-sm">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> កំណត់ឡើងវិញ (Reset)
                    </a>
                </form>
            </div>
        </div>

        <!-- Course List Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted small">
                    បានរកឃើញវគ្គសិក្សាចំនួន <strong><?= $pagination['total'] ?></strong>
                </span>
            </div>

            <?php if (empty($courses)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-search fs-1 text-muted mb-3"></i>
                    <h5 class="fw-bold">រកមិនឃើញវគ្គសិក្សាដែលត្រូវនឹងការស្វែងរករបស់អ្នកឡើយ</h5>
                    <p class="text-muted">សូមសាកល្បងផ្លាស់ប្តូរពាក្យស្វែងរក ឬសម្អាតតម្រង</p>
                    <div class="mt-2">
                        <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb">សម្អាតតម្រង</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="course-card">
                                <div class="course-thumbnail-wrapper">
                                    <img src="<?= uploadUrl('thumbnails/' . $course['thumbnail']) ?>" alt="<?= e($course['title']) ?>" class="course-thumbnail">
                                    <div class="course-badge-overlay">
                                        <?php if ($course['is_free']): ?>
                                            <span class="badge-tag badge-free">ឥតគិតថ្លៃ (Free)</span>
                                        <?php elseif ($course['is_featured']): ?>
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
                                    </div>

                                    <div class="d-flex align-items-center gap-1 mb-3 text-warning small">
                                        <i class="bi bi-star-fill"></i>
                                        <span class="fw-bold text-dark"><?= number_format($course['average_rating'], 1) ?></span>
                                        <span class="text-muted">(<?= $course['review_count'] ?>)</span>
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
                                            ចូលមើល
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_prev']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/courses?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1]))) ?>">« មុន</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
                                <li class="page-item <?= ($p == $pagination['current_page']) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/courses?' . http_build_query(array_merge($_GET, ['page' => $p]))) ?>"><?= $p ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/courses?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1]))) ?>">បន្ទាប់ »</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
