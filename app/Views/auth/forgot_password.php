<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="brand-badge fs-4 p-2 mb-2 d-inline-block"><i class="bi bi-key-fill"></i></span>
                    <h3 class="fw-bold mt-2">ភ្លេចពាក្យសម្ងាត់?</h3>
                    <p class="text-muted small">បញ្ចូលអាសយដ្ឋានអ៊ីមែលរបស់អ្នកដើម្បីទទួលបានតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ឡើងវិញ</p>
                </div>

                <?php if (\App\Helpers\Session::hasFlash('reset_debug_link')): ?>
                    <div class="alert alert-info border-0 shadow-sm p-3 mb-4 small">
                        <strong><i class="bi bi-link-45deg fs-5 me-1"></i> តំណភ្ជាប់ Reset ពាក្យសម្ងាត់ (Local Test):</strong><br>
                        <a href="<?= \App\Helpers\Session::getFlash('reset_debug_link') ?>" class="text-break fw-bold text-primary">
                            <?= \App\Helpers\Session::getFlash('reset_debug_link') ?>
                        </a>
                    </div>
                <?php endif; ?>

                <form action="<?= url('/forgot-password') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">អាសយដ្ឋានអ៊ីមែលរបស់អ្នក</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="example@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-katteb btn-lg fs-6">
                            ផ្ញើតំណភ្ជាប់កំណត់ពាក្យសម្ងាត់
                        </button>
                    </div>

                    <div class="text-center small text-muted">
                        <a href="<?= url('/login') ?>" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅទំព័រចូលគណនី
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
