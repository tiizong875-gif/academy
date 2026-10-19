<div class="py-5 bg-navy text-white text-center" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);">
    <div class="container py-4">
        <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill fw-semibold mb-2">អំពីយើង (About Us)</span>
        <h1 class="fw-bold text-white mb-3">ក្តឹបកូដ Academy</h1>
        <p class="text-secondary fs-5 mx-auto" style="max-width: 700px;">
            រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត — បេសកកម្មរបស់យើងគឺនាំយកការអប់រំបច្ចេកវិទ្យាកម្រិតពិភពលោកមកជូនកូនខ្មែរគ្រប់រូប។
        </p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5 align-items-center mb-5">
        <!-- Left Side: Vision & Mission -->
        <div class="col-lg-6">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill fw-bold mb-2">បេសកកម្ម & ចក្ខុវិស័យ</span>
            <h2 class="fw-bold mb-3">ការបណ្ដុះបណ្ដាលវិជ្ជាជីវៈកូដពិតប្រាកដ</h2>
            <p class="text-muted leading-relaxed mb-4">
                <strong>ក្តឹបកូដ Academy</strong> ត្រូវបានបង្កើតឡើងក្នុងគោលបំណងកាត់បន្ថយគម្លាតចំណេះដឹងឌីជីថលនៅកម្ពុជា។ យើងជឿជាក់ថា ការចេះសរសេរកូដមិនមែនសម្រាប់តែអ្នករៀនផ្នែក IT ប៉ុណ្ណោះទេ ប៉ុន្តែគឺជាឧបករណ៍ដ៏មានឥទ្ធិពលសម្រាប់មនុស្សគ្រប់គ្នាក្នុងការដោះស្រាយបញ្ហា និងបង្កើតតម្លៃថ្មីៗដល់សង្គម។
            </p>

            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                    <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                        <i class="bi bi-laptop fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">អនុវត្តបង្កើត Project ពិតៗ (Real-World Projects)</h6>
                        <p class="text-muted small mb-0">បង្កើត Portfolio, Website News, និង Ecommerce Platform ជាក់ស្តែង ជាមួយ HTML5, CSS3, និង Tailwind CSS។</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                    <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">ម៉ោងសិក្សាស្រួល & បត់បែន</h6>
                        <p class="text-muted small mb-0">រៀនផ្ទាល់រៀងរាល់ថ្ងៃ ច័ន្ទ-ពុធ វេលាម៉ោង 8:30 - 10:00 យប់ ងាយស្រួលសម្រាប់ទាំងសិស្ស និងអ្នកធ្វើការ។</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                    <div class="rounded-circle bg-success text-white p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; flex-shrink: 0;">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">សហគមន៍កូដខ្មែររឹងមាំ</h6>
                        <p class="text-muted small mb-0">មានក្រុម Telegram សហគមន៍សិស្សានុសិស្ស ចាំជួយដោះស្រាយកូដ Error និងចែករំលែកបទពិសោធន៍។</p>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3">
                <a href="<?= url('/courses') ?>" class="btn btn-primary-katteb px-4">
                    <i class="bi bi-collection-play me-1"></i> មើលវគ្គសិក្សាទាំងអស់
                </a>
                <a href="https://t.me/TIIZONG" target="_blank" class="btn btn-outline-success px-4">
                    <i class="bi bi-telegram me-1"></i> ចូលរួមគ្រុប Telegram
                </a>
            </div>
        </div>

        <!-- Right Side: Poster Image with Professional Frame -->
        <div class="col-lg-6">
            <div class="position-relative">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-dark">
                    <img src="<?= asset('images/about-poster.jpg') ?>?v=<?= time() ?>" class="img-fluid w-100 object-fit-cover rounded-4" alt="ក្តឹបកូដ Web Design Course">
                </div>
                <!-- Floating decorative element -->
                <div class="position-absolute bottom-0 start-0 translate-middle-y ms-3 p-3 bg-white shadow-lg rounded-3 border-start border-success border-4 d-none d-md-block" style="max-width: 250px;">
                    <div class="small fw-bold text-dark"><i class="bi bi-patch-check-fill text-success me-1"></i> វគ្គសិក្សា Web Design</div>
                    <div class="text-muted" style="font-size: 0.75rem;">HTML5 • CSS3 • Tailwind CSS</div>
                </div>
            </div>
        </div>
    </div>
</div>
