<div class="py-5 bg-navy text-white text-center" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-4">
        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill fw-semibold mb-2">ចម្ងល់ញឹកញាប់</span>
        <h1 class="fw-bold text-white mb-2">សំណួរ-ចម្លើយដែលសួរញឹកញាប់ (FAQ)</h1>
        <p class="text-secondary mx-auto" style="max-width: 600px;">
            ស្វែងរកចម្លើយរហ័សចំពោះសំណួរទាក់ទងនឹងការចុះឈ្មោះ ការបង់ប្រាក់ និងការរៀនសូត្រ
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden shadow-sm">
                        <h2 class="accordion-header" id="faqHeading<?= $faq['id'] ?>">
                            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?> fw-bold py-3 text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse<?= $faq['id'] ?>">
                                <i class="bi bi-question-circle-fill text-success me-2 fs-5"></i>
                                <?= e($faq['question_kh']) ?>
                            </button>
                        </h2>
                        <div id="faqCollapse<?= $faq['id'] ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted leading-relaxed py-3 px-4">
                                <?= nl2br(e($faq['answer_kh'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
