<?php
use App\Helpers\Str;
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin/courses') ?>" class="text-secondary">វគ្គសិក្សាទាំងអស់</a></li>
                <li class="breadcrumb-item active text-success"><?= e($course['title']) ?></li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">គ្រប់គ្រងជំពូក & មេរៀន (Sections & Lessons)</h4>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary-katteb" data-bs-toggle="modal" data-bs-target="#addSectionModal">
            <i class="bi bi-folder-plus me-1"></i> បន្ថែមជំពូកថ្មី (Add Section)
        </button>
        <a href="<?= url('/admin/courses') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>
</div>

<?php if (empty($sections)): ?>
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
        <h5 class="fw-bold">មិនទាន់មានជំពូកនៅឡើយទេ</h5>
        <p class="text-muted mb-4">សូមចុចប៊ូតុង "បន្ថែមជំពូកថ្មី" ដើម្បីចាប់ផ្ដើមរៀបចំមាតិកាវគ្គសិក្សា</p>
        <div>
            <button type="button" class="btn btn-primary-katteb px-4" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                <i class="bi bi-plus-lg me-1"></i> បន្ថែមជំពូកដំបូង
            </button>
        </div>
    </div>
<?php else: ?>
    <div class="d-flex flex-column gap-4">
        <?php foreach ($sections as $sIndex => $sec): ?>
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <!-- Section Header Bar -->
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-navy text-white rounded-pill px-3 py-1">ជំពូកទី <?= $sIndex + 1 ?></span>
                        <h6 class="fw-bold mb-0 text-dark"><?= e($sec['title']) ?></h6>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= url('/admin/courses/' . $course['id'] . '/sections/' . $sec['id'] . '/lessons/create') ?>" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-plus-circle me-1"></i> បន្ថែមមេរៀន
                        </a>

                        <form action="<?= url('/admin/courses/' . $course['id'] . '/sections/' . $sec['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកពិតជាចង់លុបជំពូកនេះ និងមេរៀនទាំងអស់ក្នុងជំពូកនេះមែនទេ?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="លុបជំពូក">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Section Lessons List -->
                <div class="card-body p-0">
                    <?php if (empty($sec['lessons'])): ?>
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-film me-1"></i> មិនទាន់មានមេរៀនក្នុងជំពូកនេះនៅឡើយទេ
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($sec['lessons'] as $les): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-play-circle-fill text-success fs-5"></i>
                                        <div>
                                            <div class="fw-bold text-dark small"><?= e($les['title']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <span>រយៈពេល៖ <?= Str::duration($les['duration_seconds']) ?></span> &bull; 
                                                <span>ប្រភេទ៖ <?= ucfirst($les['video_type']) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($les['is_free_preview']): ?>
                                            <span class="badge bg-info bg-opacity-10 text-info small">Free Preview</span>
                                        <?php endif; ?>

                                        <?php if ($les['is_published']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success small">Published</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary small">Hidden</span>
                                        <?php endif; ?>

                                        <a href="<?= url('/admin/courses/' . $course['id'] . '/lessons/' . $les['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary rounded-circle p-2" title="កែប្រែ">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="<?= url('/admin/courses/' . $course['id'] . '/lessons/' . $les['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុបមេរៀននេះមែនទេ?">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="លុប">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">បន្ថែមជំពូកថ្មី (Add Course Section)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/courses/' . $course['id'] . '/sections') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ចំណងជើងជំពូក <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="ឧ. ផ្នែកទី ១៖ មូលដ្ឋានគ្រឹះ និងបរិស្ថានអភិវឌ្ឍន៍" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">លំដាប់លំដោយ (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= count($sections) + 1 ?>">
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-katteb">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>
