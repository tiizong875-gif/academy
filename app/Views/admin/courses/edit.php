<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">កែប្រែវគ្គសិក្សា (Edit Course)</h4>
        <p class="text-muted small mb-0"><?= e($course['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/courses/' . $course['id'] . '/sections') ?>" class="btn btn-outline-primary">
            <i class="bi bi-list-task me-1"></i> រៀបចំមាតិកា & មេរៀន
        </a>
        <a href="<?= url('/admin/courses') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>
</div>

<form action="<?= url('/admin/courses/' . $course['id'] . '/update') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Left: Primary Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">ព័ត៌មានទូទៅ</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងជាភាសាខ្មែរ (Title KH) <span class="text-danger">*</span></label>
                    <input type="text" name="title_kh" class="form-control" value="<?= e($course['title_kh']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងជាភាសាអង់គ្លេស (Title EN) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($course['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាសង្ខេប (Short Description)</label>
                    <textarea name="short_description" class="form-control" rows="2"><?= e($course['short_description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាពេញលេញ (Full Description)</label>
                    <textarea name="description" class="form-control" rows="6"><?= e($course['description']) ?></textarea>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">លក្ខខណ្ឌ និងគោលបំណង</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">អ្វីដែលសិស្សនឹងទទួលបាន (Learning Objectives)</label>
                    <textarea name="learning_objectives" class="form-control" rows="4"><?= e($course['learning_objectives']) ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">លក្ខខណ្ឌតម្រូវ (Requirements)</label>
                        <textarea name="requirements" class="form-control" rows="3"><?= e($course['requirements']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">សម្រាប់អ្នកណាខ្លះ? (Target Audience)</label>
                        <textarea name="target_audience" class="form-control" rows="3"><?= e($course['target_audience']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Settings & Thumbnail -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">ការកំណត់ & គ្រូបង្រៀន</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ផ្នែកជំនាញ (Category) <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($course['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name_kh']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">គ្រូបង្រៀន (Instructor) <span class="text-danger">*</span></label>
                    <select name="instructor_id" class="form-select" required>
                        <?php foreach ($instructors as $inst): ?>
                            <option value="<?= $inst['id'] ?>" <?= ($course['instructor_id'] == $inst['id']) ? 'selected' : '' ?>>
                                <?= e($inst['name']) ?> (<?= $inst['email'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">កម្រិតសិក្សា (Level)</label>
                    <select name="level" class="form-select">
                        <option value="beginner" <?= ($course['level'] === 'beginner') ? 'selected' : '' ?>>កម្រិតដំបូង (Beginner)</option>
                        <option value="intermediate" <?= ($course['level'] === 'intermediate') ? 'selected' : '' ?>>កម្រិតមធ្យម (Intermediate)</option>
                        <option value="advanced" <?= ($course['level'] === 'advanced') ? 'selected' : '' ?>>កម្រិតខ្ពស់ (Advanced)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ស្ថានភាព (Status)</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= ($course['status'] === 'published') ? 'selected' : '' ?>>ផ្សព្វផ្សាយ (Published)</option>
                        <option value="draft" <?= ($course['status'] === 'draft') ? 'selected' : '' ?>>ពង្រាង (Draft)</option>
                        <option value="archived" <?= ($course['status'] === 'archived') ? 'selected' : '' ?>>បណ្ណសារ (Archived)</option>
                    </select>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" <?= $course['is_featured'] ? 'checked' : '' ?>>
                    <label class="form-check-label small" for="isFeatured">កំណត់ជាវគ្គឆ្នើម (Featured)</label>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_popular" id="isPopular" value="1" <?= $course['is_popular'] ? 'checked' : '' ?>>
                    <label class="form-check-label small" for="isPopular">កំណត់ជាវគ្គពេញនិយម (Popular)</label>
                </div>
            </div>

            <!-- Price -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">តម្លៃវគ្គសិក្សា</h6>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_free" id="isFree" value="1" <?= $course['is_free'] ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold text-success" for="isFree">វគ្គសិក្សាឥតគិតថ្លៃ (Free Course)</label>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">តម្លៃធម្មតា ($ USD)</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="<?= $course['price'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">តម្លៃបញ្ចុះពិសេស ($ USD)</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control" value="<?= $course['discount_price'] ?? '' ?>">
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">រូបតំណាង Thumbnail</h6>

                <?php if (!empty($course['thumbnail'])): ?>
                    <div class="mb-3">
                        <img src="<?= uploadUrl('thumbnails/' . $course['thumbnail']) ?>" class="img-fluid rounded-2 border">
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ផ្លាស់ប្តូររូប Thumbnail (16:9)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Video Preview URL</label>
                    <input type="text" name="video_preview_url" class="form-control" value="<?= e($course['video_preview_url'] ?? '') ?>">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary-katteb btn-lg">
                    <i class="bi bi-save me-1"></i> រក្សាទុកការកែប្រែ
                </button>
            </div>
        </div>
    </div>
</form>
