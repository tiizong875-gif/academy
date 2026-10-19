<?php
use App\Helpers\Str;
$isCurrentLessonCompleted = in_array($currentLesson['id'], $completedLessonIds);
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ទំព័ររៀនវីដេអូ — ក្តឹបកូដ Academy') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <style>
        body {
            background-color: #0B1120;
            color: #E2E8F0;
            overflow-x: hidden;
        }
        .learning-navbar {
            background-color: #0F172A;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            height: 65px;
        }
        .video-container {
            background-color: #000000;
            position: relative;
            width: 100%;
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .video-container video, .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
        .curriculum-sidebar {
            background-color: #0F172A;
            border-left: 1px solid rgba(255,255,255,0.08);
            height: calc(100vh - 65px);
            overflow-y: auto;
        }
        .curriculum-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            color: #94A3B8;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: all 0.2s ease;
        }
        .curriculum-item:hover {
            background-color: rgba(255,255,255,0.04);
            color: #FFFFFF;
        }
        .curriculum-item.active {
            background-color: rgba(34, 197, 94, 0.12);
            color: #22C55E;
            border-left: 3px solid #22C55E;
            font-weight: 600;
        }
    </style>
    <script>
        window.APP_URL = "<?= rtrim(url(), '/') ?>";
        window.LESSON_CONFIG = {
            courseId: <?= (int)$course['id'] ?>,
            lessonId: <?= (int)$currentLesson['id'] ?>,
            lastWatched: <?= (int)($currentProgress['last_watched_second'] ?? 0) ?>,
            isCompleted: <?= $isCurrentLessonCompleted ? 'true' : 'false' ?>
        };
    </script>
</head>
<body>

<!-- Learning Top Navbar -->
<header class="learning-navbar d-flex align-items-center justify-content-between px-3 px-md-4">
    <div class="d-flex align-items-center gap-3 text-truncate">
        <a href="<?= url('/course/' . $course['slug']) ?>" class="btn btn-sm btn-outline-light rounded-pill px-3" title="ចាកចេញ">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ក្រោយ
        </a>
        <div class="text-truncate">
            <h6 class="text-white mb-0 text-truncate fw-bold"><?= e($course['title_kh']) ?></h6>
            <small class="text-secondary"><?= e($currentLesson['title']) ?></small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Mark Complete Toggle Button -->
        <button id="btnToggleComplete" class="btn btn-sm <?= $isCurrentLessonCompleted ? 'btn-success btn-completed' : 'btn-outline-success' ?> rounded-pill px-3">
            <i class="bi <?= $isCurrentLessonCompleted ? 'bi-check-circle-fill' : 'bi-circle' ?> me-1"></i>
            <?= $isCurrentLessonCompleted ? 'បានរៀនចប់រួចរាល់' : 'កំណត់ថារៀនចប់' ?>
        </button>

        <a href="<?= url('/student/dashboard') ?>" class="btn btn-sm btn-navy rounded-pill px-3">
            <i class="bi bi-grid me-1"></i> Dashboard
        </a>
    </div>
</header>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Left: Video Player & Description -->
        <div class="col-lg-8 col-xl-9 p-3 p-md-4" style="height: calc(100vh - 65px); overflow-y: auto;">
            <!-- Player -->
            <div class="video-container mb-4">
                <?php if ($currentLesson['video_type'] === 'upload' && !empty($currentLesson['video_file'])): ?>
                    <video id="lessonVideoPlayer" controls controlsList="nodownload" preload="metadata" poster="<?= !empty($currentLesson['thumbnail']) ? uploadUrl($currentLesson['thumbnail']) : '' ?>">
                        <source src="<?= uploadUrl('videos/' . $currentLesson['video_file']) ?>" type="video/mp4">
                        កម្មវិធីរុករករបស់អ្នកមិនគាំទ្រ HTML5 Video ឡើយ។
                    </video>
                <?php elseif (str_contains($currentLesson['video_url'] ?? '', 'youtube.com') || str_contains($currentLesson['video_url'] ?? '', 'youtu.be')): ?>
                    <iframe src="<?= e($currentLesson['video_url']) ?>?enablejsapi=1" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                <?php else: ?>
                    <video id="lessonVideoPlayer" controls controlsList="nodownload" preload="metadata">
                        <source src="<?= e($currentLesson['video_url'] ?: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4') ?>" type="video/mp4">
                        កម្មវិធីរុករករបស់អ្នកមិនគាំទ្រ HTML5 Video ឡើយ។
                    </video>
                <?php endif; ?>
            </div>

            <!-- Lesson Navigation Controls -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <?php if ($prevLesson): ?>
                        <a href="<?= url('/student/learn/' . $course['slug'] . '/' . $prevLesson['slug']) ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                            <i class="bi bi-chevron-left me-1"></i> មេរៀនមុន
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" disabled>« គ្មានមេរៀនមុន</button>
                    <?php endif; ?>
                </div>

                <div>
                    <?php if ($nextLesson): ?>
                        <a href="<?= url('/student/learn/' . $course['slug'] . '/' . $nextLesson['slug']) ?>" class="btn btn-primary-katteb btn-sm rounded-pill px-3">
                            មេរៀនបន្ទាប់ <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    <?php else: ?>
                        <span class="badge bg-success bg-opacity-25 text-success p-2 rounded-pill">
                            🎉 អ្នកបានមកដល់មេរៀនចុងក្រោយ!
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lesson Title & Description -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-navy text-white mb-4" style="background-color: #0F172A;">
                <h4 class="fw-bold mb-2"><?= e($currentLesson['title']) ?></h4>
                <div class="text-secondary small mb-3">
                    <span><i class="bi bi-clock me-1 text-success"></i> <?= Str::duration($currentLesson['duration_seconds']) ?></span> &bull; 
                    <span><i class="bi bi-translate me-1 text-success"></i> ភាសាខ្មែរ (Khmer)</span>
                </div>
                <div class="text-light text-opacity-75">
                    <?= nl2br(e($currentLesson['description'] ?? 'មិនមានការពិពណ៌នាបន្ថែមសម្រាប់មេរៀននេះឡើយ។')) ?>
                </div>
            </div>
        </div>

        <!-- Right: Curriculum Sidebar -->
        <div class="col-lg-4 col-xl-3 curriculum-sidebar p-0">
            <!-- Sidebar Progress Header -->
            <div class="p-3 border-bottom border-secondary border-opacity-25 bg-dark">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-secondary fw-semibold">មាតិកាវគ្គសិក្សា</span>
                    <span class="badge bg-success bg-opacity-20 text-success small">
                        ចប់ <?= count($completedLessonIds) ?> / <?= $course['total_lessons'] ?>
                    </span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: <?= $course['total_lessons'] > 0 ? min(100, round((count($completedLessonIds) / $course['total_lessons']) * 100)) : 0 ?>%"></div>
                </div>
            </div>

            <!-- Sections & Lessons List -->
            <div class="accordion accordion-flush" id="sidebarSectionsAccordion">
                <?php foreach ($sections as $secIndex => $sec): ?>
                    <div class="accordion-item bg-transparent border-0 border-bottom border-secondary border-opacity-25">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-transparent text-white small fw-bold py-3 shadow-none <?= $secIndex > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarSec<?= $sec['id'] ?>">
                                <span><?= e($sec['title']) ?></span>
                            </button>
                        </h2>
                        <div id="sidebarSec<?= $sec['id'] ?>" class="accordion-collapse collapse show">
                            <div class="accordion-body p-0">
                                <?php foreach ($sec['lessons'] as $les): 
                                    $isThisCompleted = in_array($les['id'], $completedLessonIds);
                                    $isThisActive = ($les['id'] == $currentLesson['id']);
                                    $canAccess = $isEnrolled || $course['is_free'] || $les['is_free_preview'];
                                ?>
                                    <a href="<?= $canAccess ? url('/student/learn/' . $course['slug'] . '/' . $les['slug']) : 'javascript:void(0)' ?>" 
                                       class="curriculum-item <?= $isThisActive ? 'active' : '' ?>"
                                       data-lesson-id="<?= $les['id'] ?>">
                                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                                            <i class="status-icon bi <?= $isThisCompleted ? 'bi-check-circle-fill text-success' : ($isThisActive ? 'bi-play-circle-fill text-success' : 'bi-play-circle text-muted') ?>"></i>
                                            <span class="text-truncate small"><?= e($les['title']) ?></span>
                                        </div>
                                        <div class="ms-2 d-flex align-items-center gap-1">
                                            <?php if ($les['is_free_preview'] && !$isEnrolled): ?>
                                                <span class="badge bg-info bg-opacity-25 text-info" style="font-size: 0.65rem;">Free</span>
                                            <?php endif; ?>
                                            <span class="small text-muted" style="font-size: 0.75rem;"><?= Str::duration($les['duration_seconds']) ?></span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/player.js') ?>"></script>
</body>
</html>
