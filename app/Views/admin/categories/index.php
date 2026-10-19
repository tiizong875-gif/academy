<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">គ្រប់គ្រងផ្នែកជំនាញ (Categories)</h4>
        <p class="text-muted small mb-0">បង្កើត និងកែប្រែផ្នែកជំនាញនៃវគ្គសិក្សា</p>
    </div>
    <button type="button" class="btn btn-primary-katteb" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-circle me-1"></i> បង្កើតផ្នែកថ្មី
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted">
                    <th>Icon</th>
                    <th>ឈ្មោះជាភាសាខ្មែរ</th>
                    <th>ឈ្មោះជាភាសាអង់គ្លេស</th>
                    <th>Slug</th>
                    <th>ចំនួនវគ្គ</th>
                    <th>លំដាប់</th>
                    <th>ស្ថានភាព</th>
                    <th class="text-end">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td>
                            <div class="rounded-circle bg-light text-success p-2 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi <?= e($cat['icon']) ?> fs-5"></i>
                            </div>
                        </td>
                        <td class="fw-bold small"><?= e($cat['name_kh']) ?></td>
                        <td class="small"><?= e($cat['name']) ?></td>
                        <td><code><?= e($cat['slug']) ?></code></td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= $cat['courses_count'] ?> វគ្គសិក្សា
                            </span>
                        </td>
                        <td class="small"><?= $cat['sort_order'] ?></td>
                        <td>
                            <span class="badge-status badge-<?= $cat['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $cat['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCatModal<?= $cat['id'] ?>" title="កែប្រែ">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="<?= url('/admin/categories/' . $cat['id'] . '/delete') ?>" method="POST" class="d-inline form-confirm-action" data-confirm-message="តើអ្នកប្រាកដជាចង់លុបផ្នែកនេះដែរឬទេ?">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" title="លុប">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Category Modal -->
                    <div class="modal fade" id="editCatModal<?= $cat['id'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header border-bottom py-3">
                                    <h5 class="modal-title fw-bold">កែប្រែផ្នែក៖ <?= e($cat['name_kh']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="<?= url('/admin/categories/' . $cat['id'] . '/update') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">ឈ្មោះជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                                            <input type="text" name="name_kh" class="form-control" value="<?= e($cat['name_kh']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">ឈ្មោះជាភាសាអង់គ្លេស <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="<?= e($cat['name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                                            <input type="text" name="icon" class="form-control" value="<?= e($cat['icon']) ?>" placeholder="bi-code-slash">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">លំដាប់លំដោយ</label>
                                                <input type="number" name="sort_order" class="form-control" value="<?= $cat['sort_order'] ?>">
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center mt-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_active" id="act<?= $cat['id'] ?>" value="1" <?= $cat['is_active'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label small" for="act<?= $cat['id'] ?>">បើកដំណើរការ (Active)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">ការពិពណ៌នា</label>
                                            <textarea name="description" class="form-control" rows="3"><?= e($cat['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top py-3">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                                        <button type="submit" class="btn btn-primary-katteb">រក្សាទុកការកែប្រែ</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">បង្កើតផ្នែកជំនាញថ្មី</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('/admin/categories/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ឈ្មោះជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                        <input type="text" name="name_kh" class="form-control" placeholder="ឧ. កម្មវិធីទូរស័ព្ទ (Mobile Apps)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ឈ្មោះជាភាសាអង់គ្លេស <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Mobile App Development" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bootstrap Icon Class</label>
                        <input type="text" name="icon" class="form-control" placeholder="bi-phone" value="bi-code-slash">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">លំដាប់លំដោយ (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">ការពិពណ៌នាសង្ខេប</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="ពិពណ៌នាអំពីផ្នែកនេះ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary-katteb">បង្កើតឥឡូវនេះ</button>
                </div>
            </form>
        </div>
    </div>
</div>
