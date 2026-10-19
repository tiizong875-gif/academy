<?php
use App\Helpers\Auth;
$currentUser = Auth::user();
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ផ្ទាំងគ្រប់គ្រងរដ្ឋបាល — ក្តឹបកូដ Academy') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <!-- Chart.js for stats chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.APP_URL = "<?= rtrim(url(), '/') ?>";
    </script>
</head>
<body class="admin-body">

<!-- Sidebar -->
<aside class="admin-sidebar">
    <a href="<?= url('/admin') ?>" class="admin-brand">
        <span class="brand-badge"><i class="bi bi-shield-lock-fill"></i></span>
        <span>ក្តឹបកូដ <span class="text-success">Admin</span></span>
    </a>

    <div class="admin-nav">
        <div class="admin-nav-category">ទិដ្ឋភាពទូទៅ</div>
        <a href="<?= url('/admin') ?>" class="admin-nav-item <?= str_ends_with($currentUri, '/admin') || str_contains($currentUri, '/admin/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>

        <div class="admin-nav-category">ការគ្រប់គ្រងមាតិកា</div>
        <a href="<?= url('/admin/courses') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/courses') && !str_contains($currentUri, '/admin/lessons') ? 'active' : '' ?>">
            <i class="bi bi-collection-play"></i> <span>វគ្គសិក្សា (Courses)</span>
        </a>
        <a href="<?= url('/admin/lessons') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/lessons') ? 'active' : '' ?>">
            <i class="bi bi-camera-reels"></i> <span>វីដេអូមេរៀន (Short Lessons)</span>
        </a>
        <a href="<?= url('/admin/categories') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/categories') ? 'active' : '' ?>">
            <i class="bi bi-tags"></i> <span>ប្រភេទ (Categories)</span>
        </a>
        <a href="<?= url('/admin/reviews') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/reviews') ? 'active' : '' ?>">
            <i class="bi bi-star-half"></i> <span>មតិវាយតម្លៃ (Reviews)</span>
        </a>

        <div class="admin-nav-category">ការបញ្ជាទិញ & អ្នកប្រើប្រាស់</div>
        <a href="<?= url('/admin/orders') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/orders') ? 'active' : '' ?>">
            <i class="bi bi-receipt-cutoff"></i> <span>ការបញ្ជាទិញ & វិក្កយបត្រ</span>
        </a>
        <a href="<?= url('/admin/students') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/students') ? 'active' : '' ?>">
            <i class="bi bi-people"></i> <span>សិស្សានុសិស្ស (Students)</span>
        </a>
        <a href="<?= url('/admin/coupons') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/coupons') ? 'active' : '' ?>">
            <i class="bi bi-ticket-perforated"></i> <span>ប័ណ្ណបញ្ចុះតម្លៃ (Coupons)</span>
        </a>

        <div class="admin-nav-category">ការកំណត់ប្រព័ន្ធ</div>
        <a href="<?= url('/admin/settings') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/settings') ? 'active' : '' ?>">
            <i class="bi bi-sliders"></i> <span>ការកំណត់គេហទំព័រ & KHQR</span>
        </a>
        <a href="<?= url('/admin/logs') ?>" class="admin-nav-item <?= str_contains($currentUri, '/admin/logs') ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> <span>កំណត់ហេតុសកម្មភាព (Logs)</span>
        </a>
    </div>

    <!-- Bottom user info -->
    <div class="p-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <img src="<?= asset('images/default-avatar.svg') ?>" class="rounded-circle" width="32" height="32" alt="Admin">
            <div class="text-truncate">
                <div class="small fw-bold text-white text-truncate"><?= e($currentUser['name']) ?></div>
                <div class="text-muted" style="font-size: 0.75rem;">Administrator</div>
            </div>
        </div>
        <a href="<?= url('/logout') ?>" class="text-danger" title="ចាកចេញ"><i class="bi bi-power fs-5"></i></a>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggleBtn">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 fw-bold"><?= e($title ?? 'ផ្ទាំងគ្រប់គ្រង') ?></h5>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a href="<?= url('/') ?>" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="bi bi-box-arrow-up-right me-1"></i> មើលគេហទំព័រ
            </a>
            <div class="dropdown">
                <button class="btn btn-light d-flex align-items-center gap-2 dropdown-toggle py-1 px-3 border" data-bs-toggle="dropdown">
                    <img src="<?= asset('images/default-avatar.svg') ?>" class="rounded-circle" width="28" height="28" alt="Admin">
                    <span><?= e($currentUser['name']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item" href="<?= url('/student/profile') ?>"><i class="bi bi-person me-2"></i> គណនីផ្ទាល់ខ្លួន</a></li>
                    <li><a class="dropdown-item" href="<?= url('/admin/settings') ?>"><i class="bi bi-gear me-2"></i> ការកំណត់</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> ចាកចេញ</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Content Body -->
    <div class="admin-content-body">
        <?php if (\App\Helpers\Session::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <?= e(\App\Helpers\Session::getFlash('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (\App\Helpers\Session::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <?= e(\App\Helpers\Session::getFlash('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (\App\Helpers\Session::hasFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                <?= e(\App\Helpers\Session::getFlash('warning')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
