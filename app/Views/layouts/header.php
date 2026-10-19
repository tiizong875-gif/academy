<?php
use App\Helpers\Auth;
use App\Services\CartService;
$cartCount = CartService::count();
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'ក្តឹបកូដ Academy — រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត') ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
    <script>
        window.APP_URL = "<?= rtrim(url(), '/') ?>";
    </script>
</head>
<body>

<!-- Sticky Header -->
<header class="main-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark py-2">
            <a class="navbar-brand" href="<?= url('/') ?>" style="padding: 0; margin-right: 1.5rem; display: inline-flex; align-items: center;">
                <img src="<?= asset('images/logo.png') ?>?v=<?= time() ?>" alt="ក្តឹបកូដ Academy" class="brand-logo" style="height: 32px !important; width: auto !important; max-height: 32px !important; max-width: 110px !important; object-fit: contain !important; display: inline-block !important;">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/') ?>"><i class="bi bi-house-door me-1"></i> ទំព័រដើម</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/courses') ?>"><i class="bi bi-collection-play me-1"></i> វគ្គសិក្សាទាំងអស់</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/about') ?>">អំពីយើង</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/faq') ?>">សំណួរ-ចម្លើយ (FAQ)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/contact') ?>">ទំនាក់ទំនង</a>
                    </li>
                </ul>

                <!-- Right Actions: Search, Cart & Profile -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Cart Icon with Badge -->
                    <a href="<?= url('/cart') ?>" class="btn btn-outline-light position-relative rounded-pill px-3 py-1">
                        <i class="bi bi-cart3"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <?php if (Auth::check()): ?>
                        <div class="dropdown">
                            <button class="btn btn-navy d-flex align-items-center gap-2 dropdown-toggle px-3 py-1" type="button" data-bs-toggle="dropdown">
                                <img src="<?= $currentUser['avatar'] ? uploadUrl($currentUser['avatar']) : asset('images/default-avatar.svg') ?>" 
                                     alt="Avatar" class="rounded-circle" width="28" height="28" style="object-fit: cover;">
                                <span><?= e($currentUser['name']) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold"><?= e($currentUser['name']) ?></div>
                                    <div class="small text-muted"><?= e($currentUser['email']) ?></div>
                                </li>
                                <?php if (Auth::isAdmin()): ?>
                                    <li><a class="dropdown-item py-2 text-primary" href="<?= url('/admin') ?>"><i class="bi bi-speedometer2 me-2"></i> ផ្ទាំង Admin Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item py-2" href="<?= url('/student/dashboard') ?>"><i class="bi bi-grid me-2"></i> ផ្ទាំងគ្រប់គ្រងការសិក្សា</a></li>
                                <li><a class="dropdown-item py-2" href="<?= url('/student/my-courses') ?>"><i class="bi bi-journal-bookmark me-2"></i> វគ្គសិក្សារបស់ខ្ញុំ</a></li>
                                <li><a class="dropdown-item py-2" href="<?= url('/student/orders') ?>"><i class="bi bi-receipt me-2"></i> ប្រវត្តិបញ្ជាទិញ</a></li>
                                <li><a class="dropdown-item py-2" href="<?= url('/student/profile') ?>"><i class="bi bi-person-gear me-2"></i> ការកំណត់គណនី</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="<?= url('/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> ចាកចេញ (Logout)</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="btn btn-outline-light px-3 py-1 fw-semibold">ចូលគណនី</a>
                        <a href="<?= url('/register') ?>" class="btn btn-primary-katteb px-3 py-1">ចុះឈ្មោះ</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Flash Alerts Notifications -->
<div class="container mt-3">
    <?php if (\App\Helpers\Session::hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <?= e(\App\Helpers\Session::getFlash('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (\App\Helpers\Session::hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <?= e(\App\Helpers\Session::getFlash('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (\App\Helpers\Session::hasFlash('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <?= e(\App\Helpers\Session::getFlash('warning')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>
