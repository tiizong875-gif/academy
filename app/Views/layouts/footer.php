<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand & Info -->
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <a href="<?= url('/') ?>" class="d-inline-block">
                        <img src="<?= asset('images/logo.png') ?>" alt="ក្តឹបកូដ Academy" class="brand-logo-footer">
                    </a>
                </div>
                <p class="text-secondary mb-4">
                    វេទិកាសិក្សាវិទ្យាសាស្ត្រកុំព្យូទ័រ និងការសរសេរកូដអនឡាញឈានមុខគេជាភាសាខ្មែរ។ រៀនពីគ្រូជំនាញ មានគម្រោងអនុវត្តពិតប្រាកដ ដើម្បីឈានជើងចូលទីផ្សារការងារ IT។
                </p>
                <div class="d-flex align-items-center">
                    <a href="https://facebook.com/kattebcode" target="_blank" class="social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="https://t.me/kattebcode" target="_blank" class="social-btn"><i class="bi bi-telegram"></i></a>
                    <a href="https://youtube.com/@kattebcode" target="_blank" class="social-btn"><i class="bi bi-youtube"></i></a>
                    <a href="https://tiktok.com/@kattebcode" target="_blank" class="social-btn"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-heading">តំណភ្ជាប់រហ័ស</h5>
                <ul class="footer-links">
                    <li><a href="<?= url('/') ?>"><i class="bi bi-chevron-right small text-success"></i> ទំព័រដើម</a></li>
                    <li><a href="<?= url('/courses') ?>"><i class="bi bi-chevron-right small text-success"></i> វគ្គសិក្សាទាំងអស់</a></li>
                    <li><a href="<?= url('/about') ?>"><i class="bi bi-chevron-right small text-success"></i> អំពីយើង</a></li>
                    <li><a href="<?= url('/faq') ?>"><i class="bi bi-chevron-right small text-success"></i> សំណួរ-ចម្លើយ FAQ</a></li>
                    <li><a href="<?= url('/contact') ?>"><i class="bi bi-chevron-right small text-success"></i> ទំនាក់ទំនង</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">ផ្នែកជំនាញ</h5>
                <ul class="footer-links">
                    <li><a href="<?= url('/courses?category=1') ?>"><i class="bi bi-chevron-right small text-success"></i> Web Development</a></li>
                    <li><a href="<?= url('/courses?category=2') ?>"><i class="bi bi-chevron-right small text-success"></i> Backend Programming</a></li>
                    <li><a href="<?= url('/courses?category=3') ?>"><i class="bi bi-chevron-right small text-success"></i> Frontend Frameworks</a></li>
                    <li><a href="<?= url('/courses?category=4') ?>"><i class="bi bi-chevron-right small text-success"></i> Mobile App Development</a></li>
                    <li><a href="<?= url('/courses?category=5') ?>"><i class="bi bi-chevron-right small text-success"></i> Database & DevOps</a></li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">ព័ត៌មានទំនាក់ទំនង</h5>
                <ul class="footer-links">
                    <li class="d-flex align-items-start gap-2 text-secondary">
                        <i class="bi bi-geo-alt-fill text-success mt-1"></i>
                        <span>រាជធានីភ្នំពេញ, ព្រះរាជាណាចក្រកម្ពុជា</span>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-secondary">
                        <i class="bi bi-telephone-fill text-success"></i>
                        <span>+855 12 345 678 / 096 111 222</span>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-secondary">
                        <i class="bi bi-envelope-fill text-success"></i>
                        <span>contact@kattebcode.com</span>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-secondary">
                        <i class="bi bi-clock-fill text-success"></i>
                        <span>ច័ន្ទ - អាទិត្យ: 8:00 AM - 9:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-secondary">
            <div>
                &copy; <?= date('Y') ?> <strong>ក្តឹបកូដ Academy</strong>. រក្សាសិទ្ធិគ្រប់យ៉ាង (All rights reserved).
            </div>
            <div class="d-flex gap-4">
                <a href="<?= url('/privacy-policy') ?>" class="text-secondary text-decoration-none">គោលការណ៍ឯកជនភាព</a>
                <a href="<?= url('/terms') ?>" class="text-secondary text-decoration-none">លក្ខខណ្ឌប្រើប្រាស់</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Main App JS -->
<script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
