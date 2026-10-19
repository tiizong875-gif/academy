<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-7 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="brand-badge fs-4 p-2 mb-2 d-inline-block"><i class="bi bi-shield-lock-fill"></i></span>
                    <h3 class="fw-bold mt-2">កំណត់ពាក្យសម្ងាត់ថ្មី</h3>
                    <p class="text-muted small">សម្រាប់គណនី៖ <strong><?= e($email) ?></strong></p>
                </div>

                <form action="<?= url('/reset-password') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= e($token) ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">ពាក្យសម្ងាត់ថ្មី (New Password)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="យ៉ាងហោច ៦ តួ" required minlength="6" autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">ផ្ទៀងផ្ទាត់ពាក្យសម្ងាត់ថ្មី</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="វាយពាក្យសម្ងាត់ថ្មីម្ដងទៀត" required minlength="6">
                        </div>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-katteb btn-lg fs-6">
                            ផ្លាស់ប្តូរពាក្យសម្ងាត់ឥឡូវនេះ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
