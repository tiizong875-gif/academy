<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">បង្កើតវគ្គសិក្សាថ្មី (Create New Course)</h4>
        <p class="text-muted small mb-0">បំពេញព័ត៌មានលម្អិតនៃវគ្គសិក្សាដើម្បីចុះផ្សាយ</p>
    </div>
    <a href="<?= url('/admin/courses') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
    </a>
</div>

<form action="<?= url('/admin/courses/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row g-4">
        <!-- Left: Primary Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">ព័ត៌មានទូទៅ</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងជាភាសាខ្មែរ (Title KH) <span class="text-danger">*</span></label>
                    <input type="text" name="title_kh" class="form-control" placeholder="ឧ. វគ្គសិក្សាពេញលេញ Full-Stack Web Development ជាមួយ PHP & MySQL" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងជាភាសាអង់គ្លេស (Title EN) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Modern Full-Stack Web Development with PHP & MySQL" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាសង្ខេប (Short Description)</label>
                    <textarea name="short_description" class="form-control" rows="2" placeholder="សង្ខេបពីវគ្គសិក្សាសម្រាប់បង្ហាញលើ Course Card..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាពេញលេញ (Full Description)</label>
                    <textarea name="description" class="form-control" rows="6" placeholder="បញ្ចូលព័ត៌មានលម្អិត មាតិកា និងសេចក្តីណែនាំ..."></textarea>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom">លក្ខខណ្ឌ និងគោលបំណង</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">អ្វីដែលសិស្សនឹងទទួលបាន (Learning Objectives - មួយជួរមួយចំណុច)</label>
                    <textarea name="learning_objectives" class="form-control" rows="4" placeholder="បង្កើតគេហទំព័រ responsive&#10;ចេះរៀបចំ Database ជាមួយ MySQL&#10;យល់ច្បាស់ពី MVC Architecture"></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">លក្ខខណ្ឌតម្រូវ (Requirements)</label>
                        <textarea name="requirements" class="form-control" rows="3" placeholder="កុំព្យូទ័រ&#10;មិនទាមទារបទពិសោធន៍ពីមុន"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">សម្រាប់អ្នកណាខ្លះ? (Target Audience)</label>
                        <textarea name="target_audience" class="form-control" rows="3" placeholder="និស្សិត IT&#10;អ្នកចង់ដូរអាជីព"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Settings & Thumbnail -->
        <div class="col-lg-4">
            <!-- Publishing & Instructor -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">ការកំណត់ & គ្រូបង្រៀន</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ផ្នែកជំនាញ (Category) <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">ជ្រើសរើសផ្នែក</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= e($cat['name_kh']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">គ្រូបង្រៀន (Instructor) <span class="text-danger">*</span></label>
                    <select name="instructor_id" class="form-select" required>
                        <option value="">ជ្រើសរើសគ្រូបង្រៀន</option>
                        <?php foreach ($instructors as $inst): ?>
                            <option value="<?= $inst['id'] ?>"><?= e($inst['name']) ?> (<?= $inst['email'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">កម្រិតសិក្សា (Level)</label>
                    <select name="level" class="form-select">
                        <option value="beginner">កម្រិតដំបូង (Beginner)</option>
                        <option value="intermediate">កម្រិតមធ្យម (Intermediate)</option>
                        <option value="advanced">កម្រិតខ្ពស់ (Advanced)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ស្ថានភាព (Status)</label>
                    <select name="status" class="form-select">
                        <option value="published">ផ្សព្វផ្សាយ (Published)</option>
                        <option value="draft">ពង្រាង (Draft)</option>
                    </select>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1">
                    <label class="form-check-label small" for="isFeatured">កំណត់ជាវគ្គឆ្នើម (Featured)</label>
                </div>

                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_popular" id="isPopular" value="1">
                    <label class="form-check-label small" for="isPopular">កំណត់ជាវគ្គពេញនិយម (Popular)</label>
                </div>
            </div>

            <!-- Price & Free Option -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">តម្លៃវគ្គសិក្សា</h6>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_free" id="isFree" value="1">
                    <label class="form-check-label fw-bold text-success" for="isFree">វគ្គសិក្សាឥតគិតថ្លៃ (Free Course)</label>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">តម្លៃធម្មតា ($ USD)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="49.00" value="0.00">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">តម្លៃបញ្ចុះពិសេស ($ USD - ស្រេចចិត្ត)</label>
                    <input type="number" step="0.01" name="discount_price" class="form-control" placeholder="29.00">
                </div>
            </div>

            <!-- Thumbnail & Video Preview -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <h6 class="fw-bold mb-3 pb-2 border-bottom">រូបតំណាង & វីដេអូ Preview</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">រូប Thumbnail (16:9)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">តំណភ្ជាប់ Video Preview (YouTube/MP4)</label>
                    <input type="text" name="video_preview_url" class="form-control" placeholder="https://www.youtube.com/embed/...">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary-katteb btn-lg">
                    <i class="bi bi-save me-1"></i> រក្សាទុក & បន្តទៅរៀបចំជំពូក
                </button>
            </div>
        </div>
    </div>
</form>
