<?php
use App\Helpers\Str;
?>
<div class="mb-4">
    <h4 class="fw-bold mb-1">ការកំណត់គេហទំព័រ & វិធីសាស្ត្រទូទាត់ (Settings)</h4>
    <p class="text-muted small mb-0">គ្រប់គ្រងព័ត៌មានទូទៅ បណ្តាញសង្គម គណនីធនាគារ KHQR និង FAQs</p>
</div>

<div class="row g-4">
    <!-- General Settings Form -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-sliders text-success me-2"></i> ព័ត៌មានទូទៅនៃគេហទំព័រ</h5>

            <form action="<?= url('/admin/settings/general') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ឈ្មោះគេហទំព័រ (Site Name)</label>
                    <input type="text" name="site_name" class="form-control" value="<?= e($settings['site_name'] ?? 'ក្តឹបកូដ Academy') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">ពាក្យស្លោក (Slogan)</label>
                    <input type="text" name="site_slogan" class="form-control" value="<?= e($settings['site_slogan'] ?? 'រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត') ?>" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">អ៊ីមែលទំនាក់ទំនង</label>
                        <input type="email" name="site_email" class="form-control" value="<?= e($settings['site_email'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">លេខទូរស័ព្ទ</label>
                        <input type="text" name="site_phone" class="form-control" value="<?= e($settings['site_phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">អាសយដ្ឋាន</label>
                    <textarea name="site_address" class="form-control" rows="2"><?= e($settings['site_address'] ?? '') ?></textarea>
                </div>

                <h6 class="fw-bold mb-3 mt-4 text-dark border-top pt-3">បណ្តាញសង្គម (Social Links)</h6>

                <div class="mb-2">
                    <label class="form-label small text-muted">Facebook Page URL</label>
                    <input type="url" name="facebook_url" class="form-control form-control-sm" value="<?= e($settings['facebook_url'] ?? '') ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label small text-muted">Telegram Channel / Support</label>
                    <input type="url" name="telegram_url" class="form-control form-control-sm" value="<?= e($settings['telegram_url'] ?? '') ?>">
                </div>

                <div class="mb-2">
                    <label class="form-label small text-muted">YouTube Channel URL</label>
                    <input type="url" name="youtube_url" class="form-control form-control-sm" value="<?= e($settings['youtube_url'] ?? '') ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label small text-muted">TikTok Account URL</label>
                    <input type="url" name="tiktok_url" class="form-control form-control-sm" value="<?= e($settings['tiktok_url'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary-katteb px-4">
                    <i class="bi bi-save me-1"></i> រក្សាទុកការកំណត់ទូទៅ
                </button>
            </form>
        </div>
    </div>

    <!-- Payment Methods Config & FAQs -->
    <div class="col-lg-6">
        <!-- Payment Methods -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-qr-code-scan text-success me-2"></i> វិធីសាស្ត្រទូទាត់ប្រាក់ & KHQR</h5>

            <div class="accordion" id="pmAccordion">
                <?php foreach ($paymentMethods as $index => $pm): ?>
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?> bg-light fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#pmCollapse<?= $pm['id'] ?>">
                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                    <span><?= e($pm['name']) ?></span>
                                    <span class="badge-status badge-<?= $pm['is_active'] ? 'active' : 'inactive' ?> small">
                                        <?= $pm['is_active'] ? 'Active' : 'Disabled' ?>
                                    </span>
                                </div>
                            </button>
                        </h2>
                        <div id="pmCollapse<?= $pm['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#pmAccordion">
                            <div class="accordion-body p-4">
                                <form action="<?= url('/admin/settings/payment-method/' . $pm['id']) ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">ឈ្មោះម្ចាស់គណនី (Account Name)</label>
                                        <input type="text" name="account_name" class="form-control" value="<?= e($pm['account_name']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">លេខគណនី (Account Number)</label>
                                        <input type="text" name="account_number" class="form-control" value="<?= e($pm['account_number']) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">ការណែនាំពីរបៀបបង់ប្រាក់ (Instructions)</label>
                                        <textarea name="instructions" class="form-control" rows="3"><?= e($pm['instructions']) ?></textarea>
                                    </div>

                                    <div class="row align-items-center mb-3">
                                        <?php if (!empty($pm['qr_image'])): ?>
                                            <div class="col-auto">
                                                <img src="<?= uploadUrl('qr/' . $pm['qr_image']) ?>" class="border rounded p-1" width="70" height="70" style="object-fit: contain;">
                                            </div>
                                        <?php endif; ?>
                                        <div class="col">
                                            <label class="form-label small fw-semibold mb-1">ផ្លាស់ប្តូររូបភាព QR Code</label>
                                            <input type="file" name="qr_image" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="pmAct<?= $pm['id'] ?>" value="1" <?= $pm['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label small" for="pmAct<?= $pm['id'] ?>">បើកដំណើរការវិធីសាស្ត្រនេះ</label>
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-success px-3">
                                        <i class="bi bi-save me-1"></i> រក្សាទុក
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- FAQs Management -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-question-circle text-success me-2"></i> សំណួរ-ចម្លើយ FAQs</h5>
                <button type="button" class="btn btn-sm btn-primary-katteb" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                    <i class="bi bi-plus-lg me-1"></i> បន្ថែម FAQ
                </button>
            </div>

            <div class="d-flex flex-column gap-2 max-h-350 overflow-y-auto">
                <?php foreach ($faqs as $f): ?>
                    <div class="p-2 border rounded-3 d-flex justify-content-between align-items-center">
                        <div class="text-truncate me-2">
                            <div class="fw-bold small text-truncate"><?= e($f['question_kh']) ?></div>
                            <span class="text-muted" style="font-size: 0.75rem;"><?= Str::limit($f['answer_kh'], 60) ?></span>
                        </div>
                        <form action="<?= url('/admin/settings/faqs/' . $f['id'] . '/delete') ?>" method="POST" class="form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុប FAQ នេះមែនទេ?">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="លុប">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">បន្ថែមសំណួរ-ចម្លើយ FAQ ថ្មី</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/settings/faqs') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">សំណួរជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                        <input type="text" name="question_kh" class="form-control" placeholder="ឧ. តើខ្ញុំអាចរៀនលើទូរស័ព្ទដៃបានទេ?" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ចម្លើយជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                        <textarea name="answer_kh" class="form-control" rows="3" placeholder="ឆ្លើយតប..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-katteb">បន្ថែម FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
