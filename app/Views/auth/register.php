<div class="container py-5">
    <div class="row justify-content-center py-4">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="brand-badge fs-4 p-2 mb-2 d-inline-block"><i class="bi bi-person-plus-fill"></i></span>
                    <h3 class="fw-bold mt-2">ចុះឈ្មោះគណនីថ្មី (Register)</h3>
                    <p class="text-muted small">ចូលរួមជាមួយសហគមន៍អ្នករៀនកូដ ក្តឹបកូដ Academy</p>
                </div>

                <form action="<?= url('/register') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">ឈ្មោះពេញ (Full Name) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="ឧ. សុខ វិបុល" value="<?= old('name') ?>" required autofocus>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold small">អាសយដ្ឋានអ៊ីមែល (Email) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="example@email.com" value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">លេខទូរស័ព្ទ (Phone)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-phone text-muted"></i></span>
                                <input type="text" name="phone" class="form-control border-start-0 ps-0" placeholder="012 345 678" value="<?= old('phone') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">ពាក្យសម្ងាត់ (Password) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="យ៉ាងហោច ៦ តួ" required minlength="6">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">បញ្ជាក់ពាក្យសម្ងាត់ <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-check text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="វាយពាក្យសម្ងាត់ម្ដងទៀត" required minlength="6">
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4 small text-muted">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required checked>
                        <label class="form-check-label" for="termsCheck">
                            ខ្ញុំយល់ព្រមតាម <a href="<?= url('/terms') ?>" class="text-success text-decoration-none">លក្ខខណ្ឌប្រើប្រាស់</a> និង <a href="<?= url('/privacy-policy') ?>" class="text-success text-decoration-none">គោលការណ៍ឯកជនភាព</a>
                        </label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary-katteb btn-lg fs-6">
                            ចុះឈ្មោះបង្កើតគណនី <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                    <div class="text-center small text-muted">
                        មានគណនីរួចហើយ? 
                        <a href="<?= url('/login') ?>" class="text-success fw-bold text-decoration-none">ចូលគណនី</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
