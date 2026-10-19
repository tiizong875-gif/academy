<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-1">
                <li class="breadcrumb-item"><a href="<?= url('/admin/lessons') ?>" class="text-secondary">វីដេអូមេរៀន</a></li>
                <li class="breadcrumb-item active text-success">បន្ថែមមេរៀនថ្មី</li>
            </ol>
        </nav>
        <h4 class="fw-bold mb-0">បន្ថែមមេរៀនវីដេអូថ្មី (Upload Short Video Lesson)</h4>
    </div>
    <a href="<?= url('/admin/lessons') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
    <form action="<?= url('/admin/lessons/store') ?>" method="POST" enctype="multipart/form-data" id="lessonUploadForm">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Left Column: Primary Content & Video Files -->
            <div class="col-lg-8">
                <!-- Course & Section Selection -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">ជ្រើសរើសវគ្គសិក្សា (Course) <span class="text-danger">*</span></label>
                        <select name="course_id" id="courseSelect" class="form-select" required onchange="loadSections(this.value)">
                            <option value="">-- សូមជ្រើសរើសវគ្គសិក្សា --</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= (!empty($selectedCourse) && $selectedCourse['id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?= e($c['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">ជំពូកមេរៀន (Course Section) <span class="text-danger">*</span></label>
                        <select name="section_id" id="sectionSelect" class="form-select" required>
                            <?php if (!empty($sections)): ?>
                                <?php foreach ($sections as $s): ?>
                                    <option value="<?= $s['id'] ?>" <?= (!empty($selectedSection) && $selectedSection['id'] == $s['id']) ? 'selected' : '' ?>>
                                        <?= e($s['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">-- បង្កើតជំពូកស្វ័យប្រវត្ត (Default Section) --</option>
                            <?php endif; ?>
                        </select>
                        <small class="text-muted" style="font-size: 0.75rem;">ប្រសិនបើគ្មានជំពូក ប្រព័ន្ធនឹងបង្កើតជំពូកលំនាំដើមដោយស្វ័យប្រវត្ត</small>
                    </div>
                </div>

                <!-- Lesson Title -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold">ចំណងជើងមេរៀន (Lesson Title) <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="ឧ. មេរៀនទី ០១៖ សេចក្តីណែនាំអំពី PHP MVC Architecture" required autofocus>
                </div>

                <!-- Lesson Description -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold">ការពិពណ៌នាសង្ខេប (Short Description & Notes)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="សង្ខេបពីខ្លឹមសារមេរៀន ប្រភពកូដទាញយក ឬគន្លឹះសំខាន់ៗ..."></textarea>
                </div>

                <!-- Video Source Type & Duration -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">ប្រភពវីដេអូ (Video Source Type) <span class="text-danger">*</span></label>
                        <select name="video_type" class="form-select" id="videoTypeSelect" onchange="handleVideoTypeChange(this.value)">
                            <option value="upload" selected>Upload Video File (MP4, WebM)</option>
                            <option value="url">External Video URL (MP4 Stream)</option>
                            <option value="youtube">YouTube Embed URL</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">រយៈពេលគិតជាវិនាទី (Duration in Seconds)</label>
                        <div class="input-group">
                            <input type="number" name="duration_seconds" id="durationInput" class="form-control" placeholder="ឧ. 360" value="300">
                            <span class="input-group-text small text-muted" id="durationDisplay">5 នាទី</span>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">ពេល Upload វីដេអូ ប្រព័ន្ធអាចវាស់រយៈពេលស្វ័យប្រវត្ត</small>
                    </div>
                </div>

                <!-- Video File Upload Field -->
                <div class="mb-3" id="videoFileGroup">
                    <label class="form-label small fw-semibold">Upload ឯកសារវីដេអូ (MP4, WebM — Max 100MB)</label>
                    <input type="file" name="video_file" id="videoFileInput" class="form-control" accept="video/mp4,video/webm,video/ogg">
                    <small class="text-muted">វីដេអូ Upload នឹងត្រូវបានរក្សាទុកក្នុង Server ដើម្បីឱ្យសិស្សមើលយ៉ាងរលូន</small>

                    <!-- Realtime Local Video Preview -->
                    <div class="mt-3 d-none" id="localVideoPreviewBox">
                        <div class="ratio ratio-16x9 rounded overflow-hidden bg-black shadow-sm" style="max-height: 260px;">
                            <video id="localVideoPlayer" controls class="w-100 h-100"></video>
                        </div>
                        <small class="text-success mt-1 d-block"><i class="bi bi-check2-circle me-1"></i> បានជ្រើសរើសវីដេអូ Preview រួចរាល់</small>
                    </div>
                </div>

                <!-- Video URL Field -->
                <div class="mb-3 d-none" id="videoUrlGroup">
                    <label class="form-label small fw-semibold">តំណភ្ជាប់វីដេអូ (Video Stream / YouTube Embed URL)</label>
                    <input type="text" name="video_url" id="videoUrlInput" class="form-control" placeholder="https://commondatastorage.googleapis.com/.../sample.mp4 ឬ https://www.youtube.com/embed/...">
                    <small class="text-muted">គាំទ្រ Direct MP4 Link ឬ YouTube Embed Link</small>
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
                        <div class="mt-2 text-center d-none" id="thumbPreviewBox">
                            <img id="thumbPreviewImg" src="" class="img-fluid rounded border shadow-sm" style="max-height: 120px;" alt="Cover Preview">
                        </div>
                    </div>

                    <!-- Sort Order / Lesson Number -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">លេខរៀងមេរៀន (Display / Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="1" min="1">
                        <small class="text-muted">កំណត់លំដាប់បង្ហាញក្នុងវគ្គ (ឧ. 1 សម្រាប់ Lesson 01)</small>
                    </div>

                    <!-- Free Preview Toggle -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_free_preview" id="freePreview" value="1">
                        <label class="form-check-label small fw-bold text-success" for="freePreview">
                            អនុញ្ញាតឱ្យមើល Free Preview
                        </label>
                        <small class="text-muted d-block mt-1">សិស្សដែលមិនទាន់ទិញវគ្គ ក៏អាចចូលមើលមេរៀននេះបាន</small>
                    </div>

                    <!-- Published Toggle -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1" checked>
                        <label class="form-check-label small fw-semibold" for="isPublished">
                            ផ្សព្វផ្សាយភ្លាមៗ (Publish Immediately)
                        </label>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary-katteb btn-lg shadow-sm">
                        <i class="bi bi-cloud-arrow-up me-1"></i> រក្សាទុក & បង្ហោះមេរៀន
                    </button>
                </div>
            </div>
        </div>
    </form>
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
            document.getElementById('thumbPreviewImg').src = evt.target.result;
            document.getElementById('thumbPreviewBox').classList.remove('d-none');
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
</script>
