<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin/lessons') ?>" class="text-secondary">វីដេអូមេរៀន</a></li>
                <li class="breadcrumb-item active text-success">កែប្រែមេរៀន</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">កែប្រែមេរៀនវីដេអូ៖ <?= e($lesson['title']) ?></h4>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($lesson['course_id']) && !empty($lesson['slug'])): ?>
            <a href="<?= url('/student/learn/' . ($course['slug'] ?? '') . '/' . $lesson['slug']) ?>" target="_blank" class="btn btn-outline-info">
                <i class="bi bi-box-arrow-up-right me-1"></i> មើលក្នុង Player
            </a>
        <?php endif; ?>
        <a href="<?= url('/admin/lessons') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
    <form action="<?= url('/admin/lessons/' . $lesson['id'] . '/update') ?>" method="POST" enctype="multipart/form-data" id="lessonEditForm">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Column: Primary Content & Video Files -->
            <div class="col-lg-8">
                <!-- Course & Section Selection -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">វគ្គសិក្សា (Course) <span class="text-danger">*</span></label>
                        <select name="course_id" id="courseSelect" class="form-select" required onchange="loadSections(this.value)">
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($lesson['course_id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?= e($c['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">ជំពូកមេរៀន (Course Section) <span class="text-danger">*</span></label>
                        <select name="section_id" id="sectionSelect" class="form-select" required>
                            <?php foreach ($sections as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= ($lesson['section_id'] == $s['id']) ? 'selected' : '' ?>>
                                    <?= e($s['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Lesson Title -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងមេរៀន (Lesson Title) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= e($lesson['title']) ?>" required>
                </div>

                <!-- Lesson Description -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាសង្ខេប (Short Description & Notes)</label>
                    <textarea name="description" class="form-control" rows="3"><?= e($lesson['description']) ?></textarea>
                </div>

                <!-- Video Source Type & Duration -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">ប្រភពវីដេអូ (Video Source Type) <span class="text-danger">*</span></label>
                        <select name="video_type" class="form-select" id="videoTypeSelect" onchange="handleVideoTypeChange(this.value)">
                            <option value="upload" <?= ($lesson['video_type'] === 'upload') ? 'selected' : '' ?>>Upload Video File (MP4, WebM)</option>
                            <option value="url" <?= ($lesson['video_type'] === 'url') ? 'selected' : '' ?>>External Video URL (MP4 Stream)</option>
                            <option value="youtube" <?= ($lesson['video_type'] === 'youtube') ? 'selected' : '' ?>>YouTube Embed URL</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">រយៈពេលគិតជាវិនាទី (Duration in Seconds)</label>
                        <div class="input-group">
                            <input type="number" name="duration_seconds" id="durationInput" class="form-control" value="<?= (int)$lesson['duration_seconds'] ?>">
                            <span class="input-group-text small text-muted" id="durationDisplay">
                                <?= (int)floor($lesson['duration_seconds'] / 60) ?> នាទី
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Video File Upload & Existing Video Preview -->
                <div class="mb-3 <?= ($lesson['video_type'] !== 'upload') ? 'd-none' : '' ?>" id="videoFileGroup">
                    <label class="form-label small fw-semibold">Upload ជំនួសឯកសារវីដេអូថ្មី (Replace Video File)</label>
                    <input type="file" name="video_file" id="videoFileInput" class="form-control" accept="video/mp4,video/webm,video/ogg">
                    
                    <?php if (!empty($lesson['video_file'])): ?>
                        <div class="mt-2 p-2 bg-light rounded border d-flex align-items-center justify-content-between">
                            <div class="small text-truncate">
                                <i class="bi bi-file-earmark-play-fill text-primary me-1"></i>
                                <span>ឯកសារបច្ចុប្បន្ន៖ <b><?= e($lesson['video_file']) ?></b></span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showCurrentVideo('<?= uploadUrl('videos/' . $lesson['video_file']) ?>')">
                                <i class="bi bi-play-circle me-1"></i> Preview វីដេអូនេះ
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Realtime Local Video Preview -->
                    <div class="mt-3 d-none" id="localVideoPreviewBox">
                        <div class="ratio ratio-16x9 rounded overflow-hidden bg-black shadow-sm" style="max-height: 260px;">
                            <video id="localVideoPlayer" controls class="w-100 h-100"></video>
                        </div>
                        <small class="text-success mt-1 d-block"><i class="bi bi-check2-circle me-1"></i> បានជ្រើសរើសវីដេអូថ្មីរួចរាល់</small>
                    </div>
                </div>

                <!-- Video URL Field -->
                <div class="mb-3 <?= ($lesson['video_type'] === 'upload') ? 'd-none' : '' ?>" id="videoUrlGroup">
                    <label class="form-label small fw-semibold">តំណភ្ជាប់វីដេអូ (Video Stream / YouTube Embed URL)</label>
                    <input type="text" name="video_url" id="videoUrlInput" class="form-control" value="<?= e($lesson['video_url'] ?? '') ?>" placeholder="https://commondatastorage.googleapis.com/.../sample.mp4 ឬ https://www.youtube.com/embed/...">
                </div>
            </div>

            <!-- Right Column: Thumbnail, Settings & Submit -->
            <div class="col-lg-4">
                <div class="p-3 bg-light rounded-3 mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-gear-fill me-1 text-success"></i> ការកំណត់មេរៀន</h6>

                    <!-- Thumbnail / Cover Image Upload -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">រូបភាពក្របវីដេអូ (Cover Thumbnail)</label>
                        <input type="file" name="thumbnail" id="thumbnailInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-1">ទំហំសមាមាត្រ 16:9 (JPG, PNG, WEBP)</small>

                        <div class="mt-2 text-center" id="thumbPreviewBox">
                            <?php if (!empty($lesson['thumbnail'])): ?>
                                <img id="thumbPreviewImg" src="<?= uploadUrl($lesson['thumbnail']) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 120px;" alt="Current Cover">
                            <?php else: ?>
                                <img id="thumbPreviewImg" src="" class="img-fluid rounded border shadow-sm d-none" style="max-height: 120px;" alt="Cover Preview">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Sort Order / Lesson Number -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">លេខរៀងមេរៀន (Display / Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= (int)$lesson['sort_order'] ?>" min="1">
                    </div>

                    <!-- Free Preview Toggle -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_free_preview" id="freePreview" value="1" <?= $lesson['is_free_preview'] ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-bold text-success" for="freePreview">
                            អនុញ្ញាតឱ្យមើល Free Preview
                        </label>
                        <small class="text-muted d-block mt-1">សិស្សដែលមិនទាន់ទិញវគ្គ ក៏អាចចូលមើលមេរៀននេះបាន</small>
                    </div>

                    <!-- Published Toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1" <?= $lesson['is_published'] ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="isPublished">
                            ផ្សព្វផ្សាយ (Published)
                        </label>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary-katteb btn-lg shadow-sm">
                        <i class="bi bi-save me-1"></i> រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal for Previewing Current Video -->
<div class="modal fade" id="currentVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden bg-dark text-white">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="bi bi-play-circle me-1 text-success"></i> <?= e($lesson['title']) ?></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopCurrentVideo()"></button>
            </div>
            <div class="modal-body p-3">
                <div class="ratio ratio-16x9 rounded overflow-hidden bg-black" id="currentVideoContainer"></div>
            </div>
        </div>
    </div>
</div>

<script>
function handleVideoTypeChange(type) {
    var fileGroup = document.getElementById('videoFileGroup');
    var urlGroup = document.getElementById('videoUrlGroup');
    if (type === 'upload') {
        fileGroup.classList.remove('d-none');
        urlGroup.classList.add('d-none');
    } else {
        fileGroup.classList.add('d-none');
        urlGroup.classList.remove('d-none');
    }
}

// Preview uploaded video file and auto-calculate duration
document.getElementById('videoFileInput').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var videoPlayer = document.getElementById('localVideoPlayer');
        var previewBox = document.getElementById('localVideoPreviewBox');
        var fileUrl = URL.createObjectURL(file);
        videoPlayer.src = fileUrl;
        previewBox.classList.remove('d-none');

        videoPlayer.onloadedmetadata = function() {
            var dur = Math.round(videoPlayer.duration);
            if (dur && dur > 0) {
                document.getElementById('durationInput').value = dur;
                var mins = Math.floor(dur / 60);
                var secs = dur % 60;
                document.getElementById('durationDisplay').innerText = mins + ' នាទី ' + (secs > 0 ? secs + 's' : '');
            }
        };
    }
});

// Update duration text on manual duration change
document.getElementById('durationInput').addEventListener('input', function(e) {
    var val = parseInt(e.target.value) || 0;
    var mins = Math.floor(val / 60);
    var secs = val % 60;
    document.getElementById('durationDisplay').innerText = mins + ' នាទី ' + (secs > 0 ? secs + 's' : '');
});

// Preview thumbnail image
document.getElementById('thumbnailInput').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(evt) {
            var img = document.getElementById('thumbPreviewImg');
            img.src = evt.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// AJAX fetch sections when course changes
function loadSections(courseId) {
    if (!courseId) return;
    var select = document.getElementById('sectionSelect');
    select.innerHTML = '<option value="">កំពុងទាញយកជំពូក...</option>';

    fetch(window.APP_URL + '/admin/api/courses/' + courseId + '/sections')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            select.innerHTML = '';
            if (data.sections && data.sections.length > 0) {
                data.sections.forEach(function(sec) {
                    var opt = document.createElement('option');
                    opt.value = sec.id;
                    opt.textContent = sec.title;
                    select.appendChild(opt);
                });
            } else {
                var opt = document.createElement('option');
                opt.value = '';
                opt.textContent = '-- បង្កើតជំពូកស្វ័យប្រវត្ត (Default Section) --';
                select.appendChild(opt);
            }
        })
        .catch(function(err) {
            console.error('Error loading sections:', err);
        });
}

function showCurrentVideo(src) {
    var container = document.getElementById('currentVideoContainer');
    container.innerHTML = '<video controls autoplay controlsList="nodownload" class="w-100 h-100"><source src="' + src + '" type="video/mp4">កម្មវិធីរុករករបស់អ្នកមិនគាំទ្រ HTML5 Video ឡើយ។</video>';
    var modal = new bootstrap.Modal(document.getElementById('currentVideoModal'));
    modal.show();
}

function stopCurrentVideo() {
    var container = document.getElementById('currentVideoContainer');
    if (container) {
        container.innerHTML = '';
    }
}

document.getElementById('currentVideoModal').addEventListener('hidden.bs.modal', function () {
    stopCurrentVideo();
});
</script>
