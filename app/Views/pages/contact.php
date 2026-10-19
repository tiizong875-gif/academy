<div class="py-5 bg-navy text-white text-center" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-4">
        <span class="badge bg-success bg-opacity-20 text-success px-3 py-2 rounded-pill fw-semibold mb-2">ទំនាក់ទំនង</span>
        <h1 class="fw-bold text-white mb-2">ទាក់ទងមកកាន់យើងខ្ញុំ (Contact Us)</h1>
        <p class="text-secondary mx-auto" style="max-width: 600px;">
            មានចម្ងល់អំពីវគ្គសិក្សា ឬត្រូវការជំនួយបច្ចេកទេស? ក្រុមការងារយើងខ្ញុំរីករាយនឹងជួយលោកអ្នកជានិច្ច។
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
                <h4 class="fw-bold mb-4">ព័ត៌មានទំនាក់ទំនង</h4>

                <div class="d-flex flex-column gap-4 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">ទីស្នាក់ការ</h6>
                            <p class="text-muted small mb-0">អគារបច្ចេកវិទ្យា ក្តឹបកូដ, មហាវិថីសហព័ន្ធរុស្ស៊ី, រាជធានីភ្នំពេញ, ប្រទេសកម្ពុជា</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="bi bi-telephone-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">លេខទូរស័ព្ទ</h6>
                            <p class="text-muted small mb-0">+855 12 345 678 / +855 96 111 222</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="bi bi-envelope-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">អ៊ីមែលផ្លូវការ</h6>
                            <p class="text-muted small mb-0">contact@kattebcode.com</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3">
                            <i class="bi bi-telegram fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Telegram Support</h6>
                            <p class="text-muted small mb-0">@kattebcode (ឆ្លើយតប 24/7)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <h4 class="fw-bold mb-2">ផ្ញើសារមកកាន់យើងខ្ញុំ</h4>
                <p class="text-muted small mb-4">សូមបំពេញព័ត៌មានខាងក្រោម យើងខ្ញុំនឹងឆ្លើយតបមកវិញក្នុងរយៈពេលយ៉ាងយូរ 24 ម៉ោង</p>

                <form action="<?= url('/contact') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ឈ្មោះរបស់អ្នក <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="សុខ ពិសិដ្ឋ" value="<?= old('name') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">អាសយដ្ឋានអ៊ីមែល <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="example@email.com" value="<?= old('email') ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" class="form-control" placeholder="012 345 678" value="<?= old('phone') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">ប្រធានបទ <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="ចម្ងល់អំពីការបង់ប្រាក់..." value="<?= old('subject') ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">ខ្លឹមសារសារ <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="សរសេរសាររបស់អ្នកនៅទីនេះ..." required><?= old('message') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-katteb btn-lg px-4 fs-6">
                        <i class="bi bi-send-fill me-2"></i> ផ្ញើសារឥឡូវនេះ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
