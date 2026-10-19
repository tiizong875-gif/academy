<?php
use App\Helpers\Str;
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin') ?>" class="text-secondary">ផ្ទាំងគ្រប់គ្រង</a></li>
                <li class="breadcrumb-item active text-success">វីដេអូមេរៀនទាំងអស់</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">
            <i class="bi bi-camera-reels text-success me-2"></i>គ្រប់គ្រងវីដេអូមេរៀន (Short Video Lessons)
        </h4>
        <small class="text-muted">បង្ហោះ គ្រប់គ្រង និងរៀបចំមេរៀនវីដេអូខ្លីៗតាមលំដាប់លំដោយសម្រាប់គ្រប់វគ្គសិក្សា</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/admin/lessons/create') ?>" class="btn btn-primary-katteb px-4">
            <i class="bi bi-cloud-arrow-up-fill me-1"></i> បន្ថែមមេរៀនវីដេអូ (Upload Lesson)
        </a>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
    <form method="GET" action="<?= url('/admin/lessons') ?>" class="row g-2 align-items-center">
        <!-- Search -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមចំណងជើងមេរៀន..." value="<?= e($filters['search'] ?? '') ?>">
            </div>
        </div>

        <!-- Filter by Course -->
        <div class="col-md-4">
            <select name="course_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- វគ្គសិក្សាទាំងអស់ (All Courses) --</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= (($filters['course_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter by Status -->
        <div class="col-md-2">
            <select name="is_published" class="form-select" onchange="this.form.submit()">
                <option value="">-- ស្ថានភាព (Status) --</option>
                <option value="1" <?= (($filters['is_published'] ?? '') === '1') ? 'selected' : '' ?>>ផ្សព្វផ្សាយ (Published)</option>
                <option value="0" <?= (($filters['is_published'] ?? '') === '0') ? 'selected' : '' ?>>មិនទាន់ផ្សាយ (Draft)</option>
            </select>
        </div>

        <!-- Reset & Submit -->
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-success w-100">
                <i class="bi bi-funnel"></i> ចម្រាញ់
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['course_id']) || ($filters['is_published'] ?? '') !== ''): ?>
                <a href="<?= url('/admin/lessons') ?>" class="btn btn-outline-secondary" title="សម្អាត Filter"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Lessons Table -->
<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 70px;">រូបភាព/Preview</th>
                    <th>ព័ត៌មានមេរៀន (Lesson Title & Course)</th>
                    <th style="width: 140px;">ប្រភពវីដេអូ</th>
                    <th style="width: 110px;">រយៈពេល</th>
                    <th style="width: 90px;" class="text-center">លំដាប់</th>
                    <th style="width: 120px;" class="text-center">ស្ថានភាព</th>
                    <th style="width: 130px;" class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lessons)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-camera-reels text-muted fs-1 d-block mb-3"></i>
                            <h6 class="fw-bold text-dark">មិនទាន់មានវីដេអូមេរៀននៅឡើយទេ</h6>
                            <p class="text-muted small mb-3">សូមចុចប៊ូតុង "បន្ថែមមេរៀនវីដេអូ" ដើម្បីបង្ហោះវីដេអូខ្លីៗដំបូង</p>
                            <a href="<?= url('/admin/lessons/create') ?>" class="btn btn-sm btn-primary-katteb px-3">
                                <i class="bi bi-plus-lg me-1"></i> បន្ថែមមេរៀនថ្មី
                            </a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lessons as $item): ?>
                        <tr>
                            <!-- Thumbnail & Preview Trigger -->
                            <td>
                                <div class="position-relative ratio ratio-16x9 rounded overflow-hidden shadow-sm bg-dark" style="width: 70px; height: 42px; cursor: pointer;"
                                     onclick="previewVideo('<?= e(addslashes($item['title'])) ?>', '<?= $item['video_type'] ?>', '<?= e($item['video_file'] ? uploadUrl('videos/' . $item['video_file']) : $item['video_url']) ?>')">
                                    <?php if (!empty($item['thumbnail'])): ?>
                                        <img src="<?= uploadUrl($item['thumbnail']) ?>" class="w-100 h-100 object-fit-cover" alt="Thumbnail">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white-50">
                                            <i class="bi bi-play-circle-fill fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute top-50 start-50 translate-middle text-white bg-dark bg-opacity-50 rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px;">
                                        <i class="bi bi-play-fill" style="font-size: 0.75rem;"></i>
                                    </div>
                                </div>
                            </td>

                            <!-- Title & Course Details -->
                            <td>
                                <div class="fw-bold text-dark mb-1">
                                    <?= e($item['title']) ?>
                                    <?php if ($item['is_free_preview']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success ms-1 small" style="font-size: 0.65rem;">Free Preview</span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted d-flex flex-wrap align-items-center gap-2">
                                    <span class="badge bg-light text-secondary border">
                                        <i class="bi bi-collection-play me-1"></i><?= e($item['course_title']) ?>
                                    </span>
                                    <span>&bull;</span>
                                    <span>ជំពូក៖ <?= e($item['section_title']) ?></span>
                                </div>
                            </td>

                            <!-- Video Source Type -->
                            <td>
                                <?php if ($item['video_type'] === 'upload'): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-file-earmark-play-fill me-1"></i> MP4 Upload
                                    </span>
                                <?php elseif ($item['video_type'] === 'youtube'): ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-youtube me-1"></i> YouTube
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        <i class="bi bi-link-45deg me-1"></i> Stream URL
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Duration -->
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-stopwatch me-1 text-muted"></i><?= Str::duration($item['duration_seconds']) ?>
                                </span>
                            </td>

                            <!-- Sort Order -->
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-dark fw-bold px-2 py-1">
                                    #<?= (int)$item['sort_order'] ?>
                                </span>
                            </td>

                            <!-- Publish Status Toggle -->
                            <td class="text-center">
                                <form action="<?= url('/admin/lessons/' . $item['id'] . '/toggle') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent" title="ចុចដើម្បីប្តូរស្ថានភាព">
                                        <?php if ($item['is_published']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2">
                                                <i class="bi bi-check-circle-fill me-1"></i> ផ្សព្វផ្សាយ
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border py-1 px-2">
                                                <i class="bi bi-eye-slash-fill me-1"></i> មិនទាន់ផ្សាយ
                                            </span>
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </td>

                            <!-- Action Buttons -->
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Quick Preview -->
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-circle" title="Preview វីដេអូ"
                                            onclick="previewVideo('<?= e(addslashes($item['title'])) ?>', '<?= $item['video_type'] ?>', '<?= e($item['video_file'] ? uploadUrl('videos/' . $item['video_file']) : $item['video_url']) ?>')">
                                        <i class="bi bi-play-circle"></i>
                                    </button>

                                    <!-- Edit -->
                                    <a href="<?= url('/admin/lessons/' . $item['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary rounded-circle" title="កែប្រែ">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Delete -->
                                    <form action="<?= url('/admin/lessons/' . $item['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុបមេរៀន '<?= e(addslashes($item['title'])) ?>' នេះមែនទេ?">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="លុប">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
        <div class="card-footer bg-white border-top py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="small text-muted">
                បង្ហាញមេរៀនទី <b><?= count($lessons) ?></b> ក្នុងចំណោមសរុប <b><?= $pagination['total'] ?></b> មេរៀន
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <?php if ($pagination['has_prev']): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/admin/lessons?page=' . ($pagination['current_page'] - 1) . '&course_id=' . ($filters['course_id'] ?? '') . '&search=' . urlencode($filters['search'] ?? '') . '&is_published=' . ($filters['is_published'] ?? '')) ?>">មុន</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $pagination['total_pages']; $p++): ?>
                        <li class="page-item <?= ($p == $pagination['current_page']) ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/admin/lessons?page=' . $p . '&course_id=' . ($filters['course_id'] ?? '') . '&search=' . urlencode($filters['search'] ?? '') . '&is_published=' . ($filters['is_published'] ?? '')) ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagination['has_next']): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= url('/admin/lessons?page=' . ($pagination['current_page'] + 1) . '&course_id=' . ($filters['course_id'] ?? '') . '&search=' . urlencode($filters['search'] ?? '') . '&is_published=' . ($filters['is_published'] ?? '')) ?>">បន្ទាប់</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Video Preview Modal -->
<div class="modal fade" id="videoPreviewModal" tabindex="-1" aria-labelledby="videoPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden bg-dark text-white">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="videoPreviewModalLabel"><i class="bi bi-play-circle me-1"></i> Preview វីដេអូ</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopPreview()"></button>
            </div>
            <div class="modal-body p-3">
                <div class="ratio ratio-16x9 rounded overflow-hidden bg-black" id="previewPlayerContainer">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewVideo(title, type, src) {
    document.getElementById('videoPreviewModalLabel').innerHTML = '<i class="bi bi-play-circle me-1 text-success"></i> ' + title;
    var container = document.getElementById('previewPlayerContainer');
    
    if (type === 'youtube') {
        container.innerHTML = '<iframe src="' + src + '?autoplay=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-100 h-100 border-0"></iframe>';
    } else {
        container.innerHTML = '<video controls autoplay controlsList="nodownload" class="w-100 h-100"><source src="' + src + '" type="video/mp4">កម្មវិធីរុករករបស់អ្នកមិនគាំទ្រ HTML5 Video ឡើយ។</video>';
    }

    var modal = new bootstrap.Modal(document.getElementById('videoPreviewModal'));
    modal.show();
}

function stopPreview() {
    var container = document.getElementById('previewPlayerContainer');
    if (container) {
        container.innerHTML = '';
    }
}

document.getElementById('videoPreviewModal').addEventListener('hidden.bs.modal', function () {
    stopPreview();
});
</script>
