<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="brand-badge fs-4 p-2 mb-2 d-inline-block"><i class="bi bi-code-slash"></i></span>
                    <h3 class="fw-bold mt-2">ចូលគណនី (Login)</h3>
                    <p class="text-muted small">ចូលទៅកាន់គណនីរបស់អ្នកដើម្បីបន្តការរៀនសូត្រ</p>
                </div>

                <form action="<?= url('/login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">អាសយដ្ឋានអ៊ីមែល (Email)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="example@email.com" value="<?= old('email') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label fw-semibold small">ពាក្យសម្ងាត់ (Password)</label>
                            <a href="<?= url('/forgot-password') ?>" class="small text-success text-decoration-none">ភ្លេចពាក្យសម្ងាត់?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid mb-3 mt-4">
                        <button type="submit" class="btn btn-primary-katteb btn-lg fs-6">
                            ចូលគណនី <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </div>

                    <!-- Demo login credentials helper for convenience -->
                    <div class="p-3 bg-light rounded-3 small text-muted mb-4 border">
                        <div class="fw-bold mb-1 text-dark"><i class="bi bi-info-circle-fill text-success me-1"></i> គណនីសាកល្បង (Demo Accounts):</div>
                        <div><strong>Admin:</strong> admin@kattebcode.com / <code>Admin@12345</code></div>
                        <div><strong>Student:</strong> student@kattebcode.com / <code>Student@12345</code></div>
                    </div>

                    <div class="text-center small text-muted">
                        មិនទាន់មានគណនីនៅឡើយ? 
                        <a href="<?= url('/register') ?>" class="text-success fw-bold text-decoration-none">ចុះឈ្មោះឥឡូវនេះ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
