<div class="py-4 bg-navy text-white" style="background-color: var(--navy-dark);">
    <div class="container py-2">
        <h2 class="fw-bold text-white mb-1"><i class="bi bi-person-gear me-2 text-success"></i> ការកំណត់គណនីផ្ទាល់ខ្លួន (Account Settings)</h2>
        <p class="text-secondary mb-0">កែប្រែព័ត៌មានផ្ទាល់ខ្លួន រូបតំណាង (Avatar) និងផ្លាស់ប្តូរពាក្យសម្ងាត់</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Profile Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h5 class="fw-bold mb-4 pb-2 border-bottom"><i class="bi bi-person-circle text-success me-2"></i> ព័ត៌មានផ្ទាល់ខ្លួន</h5>

                <form action="<?= url('/student/profile') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="d-flex align-items-center gap-4 mb-4">
                        <img src="<?= $user['avatar'] ? uploadUrl($user['avatar']) : asset('images/default-avatar.svg') ?>" class="rounded-circle border border-2 border-success" width="80" height="80" style="object-fit: cover;" alt="Avatar">
                        <div>
                            <label class="form-label small fw-semibold mb-1">ផ្លាស់ប្តូររូបថត Profile</label>
                            <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">ប្រភេទអនុញ្ញាត៖ JPG, PNG, WEBP (អតិបរមា 5MB)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ឈ្មោះពេញ (Full Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">អាសយដ្ឋានអ៊ីមែល</label>
                            <input type="email" class="form-control bg-light" value="<?= e($user['email']) ?>" readonly disabled>
                            <small class="text-muted">អ៊ីមែលមិនអាចកែប្រែបានឡើយ</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" placeholder="012 345 678">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ចំណងជើងសង្ខេប (Headline)</label>
                        <input type="text" name="headline" class="form-control" value="<?= e($user['headline'] ?? '') ?>" placeholder="ឧ. Junior Web Developer">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">អំពីខ្លួនអ្នក (Bio)</label>
                        <textarea name="bio" class="form-control" rows="3" placeholder="សរសេរពិពណ៌នាអំពីខ្លួនអ្នកសង្ខេប..."><?= e($user['bio'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-katteb px-4">
                        <i class="bi bi-save me-1"></i> រក្សាទុកការកែប្រែ
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h5 class="fw-bold mb-4 pb-2 border-bottom"><i class="bi bi-lock-fill text-success me-2"></i> ផ្លាស់ប្តូរពាក្យសម្ងាត់</h5>

                <form action="<?= url('/student/password') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ពាក្យសម្ងាត់បច្ចុប្បន្ន <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ពាក្យសម្ងាត់ថ្មី <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" placeholder="យ៉ាងហោច ៦ តួ" required minlength="6">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី <span class="text-danger">*</span></label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="វាយពាក្យសម្ងាត់ថ្មីម្ដងទៀត" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-navy px-4">
                        <i class="bi bi-shield-check me-1"></i> ផ្លាស់ប្តូរពាក្យសម្ងាត់
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
