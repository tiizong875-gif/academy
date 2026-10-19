<?php
use App\Helpers\Str;
?>

<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <h2 class="fw-bold text-white mb-1"><i class="bi bi-cart3 me-2 text-success"></i> កន្ត្រកទំនិញរបស់អ្នក (Shopping Cart)</h2>
        <p class="text-secondary mb-0">ពិនិត្យមើលវគ្គសិក្សាដែលអ្នកបានជ្រើសរើស មុនពេលបន្តទៅកាន់ការទូទាត់ប្រាក់</p>
    </div>
</div>

<div class="container py-5">
    <?php if ($cart['count'] === 0): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
            <h4 class="fw-bold">កន្ត្រកទំនិញរបស់អ្នកទទេស្អាត</h4>
            <p class="text-muted mb-4">អ្នកមិនទាន់បានជ្រើសរើសវគ្គសិក្សាណាមួយចូលក្នុងកន្ត្រកនៅឡើយទេ</p>
            <div>
                <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb px-4">
                    <i class="bi bi-collection-play me-2"></i> រុករកវគ្គសិក្សាឥឡូវនេះ
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                        <h5 class="fw-bold mb-0">វគ្គសិក្សាក្នុងកន្ត្រក (<?= $cart['count'] ?>)</h5>
                        <span class="text-muted small">ធានាការចូលរៀនបានរហូត</span>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($cart['items'] as $item): ?>
                            <div class="p-3 border rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= uploadUrl('thumbnails/' . $item['thumbnail']) ?>" alt="<?= e($item['title']) ?>" class="rounded-3" width="100" height="60" style="object-fit: cover;">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            <a href="<?= url('/course/' . $item['slug']) ?>" class="text-dark text-decoration-none">
                                                <?= e($item['title_kh']) ?>
                                            </a>
                                        </h6>
                                        <div class="small text-muted">
                                            <span><i class="bi bi-play-btn me-1"></i> <?= $item['total_lessons'] ?> មេរៀន</span> &bull; 
                                            <span><i class="bi bi-clock me-1"></i> <?= Str::durationHours($item['duration_minutes']) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-4">
                                    <div class="text-end">
                                        <div class="fw-bold fs-5 text-dark">
                                            <?= $item['effective_price'] == 0 ? '<span class="text-success">Free</span>' : '$' . number_format($item['effective_price'], 2) ?>
                                        </div>
                                        <?php if ($item['discount_price'] !== null && $item['discount_price'] < $item['price']): ?>
                                            <small class="text-muted text-decoration-line-through">$<?= number_format($item['price'], 2) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?= url('/cart/remove/' . $item['id']) ?>" class="btn btn-outline-danger btn-sm rounded-circle p-2" title="ដកចេញ">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Summary & Coupon -->
            <div class="col-lg-4">
                <!-- Coupon Box -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-ticket-perforated text-success me-2"></i> ប័ណ្ណបញ្ចុះតម្លៃ (Coupon Code)</h6>
                    
                    <?php if ($cart['coupon']): ?>
                        <div class="alert alert-success d-flex justify-content-between align-items-center py-2 mb-0">
                            <div>
                                <strong><?= e($cart['coupon']['code']) ?></strong>
                                <div class="small text-success">បញ្ចុះតម្លៃ $<?= number_format($cart['discount'], 2) ?></div>
                            </div>
                            <a href="<?= url('/cart/coupon/remove') ?>" class="btn btn-sm btn-outline-danger py-0 px-2">ដកចេញ</a>
                        </div>
                    <?php else: ?>
                        <form action="<?= url('/cart/coupon') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" placeholder="បញ្ចូលកូដ (ឧ. KATTEB2026)" required>
                                <button type="submit" class="btn btn-success">អនុវត្ត</button>
                            </div>
                            <small class="text-muted mt-2 d-block">សាកល្បងកូដ <code>KATTEB2026</code> ដើម្បីបញ្ចុះតម្លៃ 20%</small>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Order Summary -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3">សង្ខេបការបញ្ជាទិញ</h5>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">តម្លៃសរុប (Subtotal)</span>
                        <span class="fw-semibold">$<?= number_format($cart['subtotal'], 2) ?></span>
                    </div>

                    <?php if ($cart['discount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span>បញ្ចុះតម្លៃ (Discount)</span>
                            <span class="fw-semibold">-$<?= number_format($cart['discount'], 2) ?></span>
                        </div>
                    <?php endif; ?>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5">ទឹកប្រាក់ត្រូវបង់</span>
                        <span class="fw-bold fs-3 text-success">$<?= number_format($cart['total'], 2) ?></span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?= url('/checkout') ?>" class="btn btn-primary-katteb btn-lg">
                            បន្តទៅការទូទាត់ប្រាក់ <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                        <a href="<?= url('/courses') ?>" class="btn btn-outline-secondary btn-sm rounded-pill py-2">
                            ជ្រើសរើសវគ្គសិក្សាបន្ថែម
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
