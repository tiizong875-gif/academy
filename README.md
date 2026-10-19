# ក្តឹបកូដ Academy — Online Video Learning Platform

> **ពាក្យស្លោក (Slogan):** រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត  
> **Brand Identity:** Dark Navy (`#0F172A`), Green Accent (`#22C55E`), Clean Light Content (`#F8FAFC`)  
> **Khmer Typography:** Kantumruy Pro & Plus Jakarta Sans  

**ក្តឹបកូដ Academy** គឺជាគេហទំព័ររៀនកូដ និងវិទ្យាសាស្ត្រកុំព្យូទ័រអនឡាញពេញលេញ (Full-Stack Online Video Learning Platform) ដែលត្រូវបានបង្កើតឡើងយ៉ាងទំនើប ស្រស់ស្អាត Responsive ដំណើរការបានយ៉ាងរលូនលើកុំព្យូទ័រ ថេប្លេត និងទូរស័ព្ទដៃ។ គម្រោងនេះត្រូវបានកសាងឡើងតាមស្ថាបត្យកម្មស្អាត **PHP 8+ MVC Architecture**, **MySQL PDO Prepared Statements**, **Bootstrap 5**, និងប្រព័ន្ធទូទាត់ប្រាក់ **Manual Payment Workflow (Bakong KHQR)**។

---

## ១. បច្ចេកវិទ្យាដែលបានប្រើប្រាស់ (Technology Stack)

- **Frontend:** HTML5, CSS3, Modern JavaScript (Fetch API, AJAX), Bootstrap 5.3, Bootstrap Icons, Chart.js
- **Typography:** Kantumruy Pro (Khmer font) និង Plus Jakarta Sans
- **Backend:** PHP 8.2+ ប្រើប្រាស់ Clean MVC-style architecture (Controllers, Models, Views, Middlewares, Helpers, Services)
- **Database:** MySQL / MariaDB ប្រើប្រាស់ PDO Prepared Statements 100% ការពារ SQL Injection
- **Authentication:** PHP Sessions ជាមួយ Secure Session Cookie, Session Regeneration, និង BCrypt Password Hashing (`password_hash`)
- **Payment System:** Manual Payment Workflow ជាមួយ KHQR QR Code (ABA, Wing, ACLEDA), បង្កាន់ដៃ Upload និងការផ្ទៀងផ្ទាត់ដោយ Admin
- **Video Learning:** HTML5 Video Player ទំនើប ជាមួយ Curriculum Sidebar, Auto Resume playback, និង AJAX Progress Tracking

---

## ២. រចនាសម្ព័ន្ធថត និងឯកសារ (Project Folder Structure)

```text
c:\xampp\htdocs\academy/
├── app/
│   ├── Controllers/
│   │   ├── Controller.php               # Base Controller
│   │   ├── HomeController.php           # Landing Page, Testimonials, Categories
│   │   ├── CourseController.php         # Catalog, Filters, Search, Details, Reviews
│   │   ├── AuthController.php           # Login, Register, Logout, Forgot/Reset Password
│   │   ├── CartController.php           # Shopping Cart, Add/Remove, Coupons
│   │   ├── OrderController.php          # Checkout, Invoice, Upload Receipt
│   │   ├── StudentController.php        # Student Dashboard, My Courses, Video Learning, Profile
│   │   ├── PageController.php           # About, Contact (Form submit), FAQ, Terms, Privacy, 404
│   │   └── Admin/
│   │       ├── AdminController.php      # Base Admin Guard
│   │       ├── DashboardController.php  # Real-time Metrics, Monthly Sales Chart
│   │       ├── CourseAdminController.php# Course CRUD, Featured/Popular toggle, Sections
│   │       ├── LessonAdminController.php# Lesson CRUD, Video URL/File upload, Free Preview
│   │       ├── CategoryAdminController.php # Category CRUD & Delete Protection
│   │       ├── StudentAdminController.php  # Student list, Search, Profile, Toggle Status
│   │       ├── OrderAdminController.php    # Orders review, Receipt check, Approve/Reject
│   │       ├── CouponAdminController.php   # Coupon discount management
│   │       ├── ReviewAdminController.php   # Course review moderation
│   │       ├── SettingAdminController.php  # Website settings, Payment KHQR methods, FAQs
│   │       └── LogAdminController.php      # Admin Activity Audit Trail
│   ├── Core/
│   │   ├── App.php                      # Application Bootstrap & Session Start
│   │   ├── Database.php                 # PDO Singleton Connection
│   │   ├── Request.php                  # HTTP Request, Input Sanitization, Headers, JSON
│   │   ├── Response.php                 # JSON Response, Redirects, 404 handler
│   │   └── Router.php                   # Regex URL Router, Parameters, Middlewares, Groups
│   ├── Helpers/
│   │   ├── Auth.php                     # Authentication state, Role-based checks
│   │   ├── Csrf.php                     # CSRF Token generator and validator
│   │   ├── Session.php                  # Flash messages, Session management
│   │   ├── Upload.php                   # Secure File Upload (MIME, size, extension whitelist)
│   │   ├── Str.php                      # Khmer friendly slug, duration formatter, currency
│   │   └── View.php                     # View renderer, url(), asset(), e() escaping
│   ├── Middleware/
│   │   ├── AuthMiddleware.php           # Student route protection
│   │   └── AdminMiddleware.php          # Admin dashboard authorization check
│   ├── Models/                          # 20 Database Model Classes
│   │   ├── Model.php, User.php, Category.php, Course.php, CourseSection.php, Lesson.php...
│   │   └── Payment.php, PaymentMethod.php, Order.php, Coupon.php, Review.php, Faq.php...
│   ├── Services/
│   │   └── CartService.php              # Session-based Shopping Cart & Coupon Calculations
│   └── Views/
│       ├── layouts/                     # main.php, header.php, footer.php, admin.php...
│       ├── home/index.php               # Hero, stats, categories, courses, testimonials
│       ├── courses/                     # index.php, show.php
│       ├── auth/                        # login.php, register.php, forgot_password.php...
│       ├── cart/                        # index.php, checkout.php
│       ├── student/                     # dashboard.php, my_courses.php, learn.php, orders.php...
│       ├── pages/                       # about.php, contact.php, faq.php, terms.php, 404.php
│       └── admin/                       # Dashboard, Courses, Sections, Lessons, Orders, Settings...
├── config/
│   ├── app.php                          # App constants, timezone, currency, limits
│   └── database.php                     # PDO MySQL credentials
├── database/
│   ├── database.sql                     # Full schema (20 tables) & rich demo data
│   └── generate_assets.php              # Placeholder SVG image generator
├── public/
│   ├── index.php                        # Front Controller with PSR-4 Autoloading
│   ├── .htaccess                        # Apache URL Rewrite rules
│   ├── assets/                          # CSS (style.css, admin.css), JS (main.js, player.js)
│   └── uploads/                         # Thumbnails, Profiles, Receipts, QR codes
├── storage/
│   ├── logs/                            # Application logs
│   └── private-videos/                  # Private self-hosted video storage
├── .env.example                         # Environment configuration example
├── .gitignore
├── .htaccess                            # Root dispatcher rewrite to public/
├── index.php                            # Root entry fallback
└── README.md                            # Comprehensive Documentation
```

---

## ៣. តារាងទិន្នន័យ MySQL (20 Database Tables)

ប្រព័ន្ធទិន្នន័យត្រូវបានរចនាឡើងយ៉ាងត្រឹមត្រូវតាមស្តង់ដារ Relational Database មាន Primary Keys, Foreign Keys, Indexes និងទិន្នន័យគំរូ (Demo Data)៖

1. `users` — គណនីសិស្ស, គ្រូបង្រៀន និង Admin (BCrypt hashed passwords)
2. `categories` — ប្រភេទផ្នែកជំនាញនៃវគ្គសិក្សា (Web Dev, Backend, Mobile...)
3. `courses` — ព័ត៌មានវគ្គសិក្សា, តម្លៃ, ការបញ្ចុះតម្លៃ, កម្រិត, rating
4. `course_sections` — ជំពូកនៃវគ្គសិក្សានីមួយៗ
5. `lessons` — មេរៀនវីដេអូ, រយៈពេល, ប្រភេទវីដេអូ, Free preview toggle
6. `enrollments` — កំណត់ត្រាសិទ្ធិចូលរៀនរបស់សិស្ស (Active, Expired, Revoked)
7. `lesson_progress` — វឌ្ឍនភាពសិក្សា (Last watched seconds, Completed status)
8. `orders` — ការបញ្ជាទិញ (Order number, Subtotal, Discount, Total, Status)
9. `order_items` — មុខទំនិញវគ្គសិក្សាក្នុងវិក្កយបត្រនីមួយៗ
10. `payments` — កំណត់ត្រាបង់ប្រាក់, បង្កាន់ដៃ (Receipt image), ការផ្ទៀងផ្ទាត់ដោយ Admin
11. `payment_methods` — វិធីសាស្ត្រទូទាត់ប្រាក់ (ABA KHQR, Wing, ACLEDA)
12. `coupons` — ប័ណ្ណបញ្ចុះតម្លៃ (Percentage / Fixed discount, Expiry date, Usage limit)
13. `coupon_usages` — កំណត់ត្រាការប្រើប្រាស់កូដបញ្ចុះតម្លៃ
14. `reviews` — មតិវាយតម្លៃ និងផ្កាយ (Ratings & Comments) ពីសិស្សដែលបានចុះឈ្មោះ
15. `wishlists` — បញ្ជីពេញចិត្តរបស់សិស្ស
16. `contact_messages` — សារទំនាក់ទំនងពីទម្រង់ Contact Us
17. `faqs` — សំណួរ-ចម្លើយដែលសួរញឹកញាប់ (FAQ) ជាភាសាខ្មែរ
18. `site_settings` — ការកំណត់គេហទំព័រ (Logo, Slogan, Social Links, Contacts)
19. `admin_activity_logs` — កំណត់ហេតុសកម្មភាពរដ្ឋបាល (Audit Trail)
20. `password_resets` — Token សម្រាប់កំណត់ពាក្យសម្ងាត់ថ្មី (Password Reset Tokens)

---

## ៤. របៀបតម្លើង និងដំណើរការជាមួយ XAMPP (Installation Guide)

### តម្រូវការប្រព័ន្ធ (System Requirements):
- ប្រព័ន្ធប្រតិបត្តិការ Windows 10/11
- កម្មវិធី **XAMPP** (PHP 8.0 ឬខ្ពស់ជាងនេះ)
- PHP Extensions ដែលចាំបាច់៖ `pdo_mysql`, `mbstring`, `openssl`, `fileinfo` (បើកស្រាប់ក្នុង XAMPP)
- កម្មវិធីរុករក Modern Web Browser (Chrome, Edge, Firefox, Safari)

### ជំហានទី ១៖ ដាក់គម្រោងក្នុង XAMPP htdocs
ត្រូវប្រាកដថា Folder គម្រោងស្ថិតនៅ៖
```text
C:\xampp\htdocs\academy
```

### ជំហានទី ២៖ ចាប់ផ្ដើម Apache និង MySQL ក្នុង XAMPP
1. បើកកម្មវិធី **XAMPP Control Panel**
2. ចុចប៊ូតុង **Start** នៅត្រង់ **Apache**
3. ចុចប៊ូតុង **Start** នៅត្រង់ **MySQL**

### ជំហានទី ៣៖ បង្កើត និង Import Database តាមរយៈ phpMyAdmin
1. បើកកម្មវិធី Browser រួចចូលទៅកាន់៖ `http://localhost/phpmyadmin/`
2. ចុចលើ Tab **Import**
3. ចុច **Choose File** រួចជ្រើសរើសឯកសារ៖
   ```text
   C:\xampp\htdocs\academy\database\database.sql
   ```
4. ចុចប៊ូតុង **Import** (ឬ **Go**) នៅខាងក្រោមបង្អស់។ ប្រព័ន្ធនឹងបង្កើត Database ឈ្មោះ `katteb_academy` រួមទាំងតារាងទាំង ២០ និងទិន្នន័យគំរូដោយស្វ័យប្រវត្តិ។

*(ចំណាំ៖ Database `katteb_academy` ត្រូវបានបង្កើត និង Import រួចរាល់ស្រាប់ក្នុងម៉ាស៊ីននេះ)*

### ជំហានទី ៤៖ ចូលទស្សនាគេហទំព័រ
លោកអ្នកអាចបើកមើលគេហទំព័រតាមរយៈអាសយដ្ឋាន៖
```text
http://localhost/academy
```
ឬ
```text
http://localhost/academy/public
```

---

## ៥. គណនីគំរូសម្រាប់សាកល្បងប្រព័ន្ធ (Demo Accounts)

### ១. គណនីរដ្ឋបាល (Admin Account):
- **URL ចូល:** `http://localhost/academy/login` (ឬចូលកាន់ `http://localhost/academy/admin`)
- **Email:** `admin@kattebcode.com`
- **Password:** `Admin@12345`
- **សិទ្ធិ:** គ្រប់គ្រងវគ្គសិក្សា, មេរៀន, អនុម័តការបង់ប្រាក់, មើល Dashboard, គ្រប់គ្រងសិស្ស, ប័ណ្ណបញ្ចុះតម្លៃ, ការកំណត់ KHQR

### ២. គណនីសិស្ស (Student Account):
- **Email:** `student@kattebcode.com`
- **Password:** `Student@12345`
- **សិទ្ធិ:** ចុះឈ្មោះរៀនវគ្គសិក្សា, ទិញវគ្គ, Upload បង្កាន់ដៃបង់ប្រាក់, រៀនវីដេអូ, តាមដានវឌ្ឍនភាពមេរៀន, កែប្រែ Profile

### ៣. ការចុះឈ្មោះសិស្សថ្មី៖
លោកអ្នកក៏អាចចុះឈ្មោះគណនីសិស្សថ្មីភ្លាមៗនៅទំព័រ `http://localhost/academy/register`។

---

## ៦. លំហូរការងារទិញវគ្គសិក្សា & ការបង់ប្រាក់ (Purchase & Payment Workflow)

1. **សិស្សចូលគណនី ឬចុះឈ្មោះ:** ចូលទៅកាន់ `/login` ឬ `/register`។
2. **ជ្រើសរើសវគ្គសិក្សា:** ចូលទៅកាន់ `/courses` រួចចុចមើលព័ត៌មានលម្អិតវគ្គសិក្សា។
3. **បន្ថែមចូលកន្ត្រក:** ចុច "បន្ថែមទៅកន្ត្រកទំនិញ" (`/cart`)។
4. **អនុវត្តកូដបញ្ចុះតម្លៃ:** បញ្ចូលកូដ `KATTEB2026` លើទំព័រកន្ត្រក ដើម្បីទទួលបានការបញ្ចុះតម្លៃ 20%។
5. **បន្តទៅការទូទាត់ (Checkout):** ជ្រើសរើសវិធីសាស្ត្រទូទាត់ប្រាក់ (ABA Bank KHQR, Wing Bank, ឬ ACLEDA Bank)។
6. **បង្កើតការបញ្ជាទិញ:** ចុច "បញ្ជាក់ការបញ្ជាទិញ"។ ប្រព័ន្ធនឹងបង្កើតវិក្កយបត្រថ្មី (ឧ. `ORD-2026...`) ដោយមានស្ថានភាព `pending`។
7. **ស្កេន KHQR និង Upload បង្កាន់ដៃ:** សិស្សមើលរូបភាព KHQR និងលេខគណនី រួចធ្វើការ Upload រូបភាពបង្កាន់ដៃ (Screenshot) នៃការផ្ទេរប្រាក់។
8. **Admin ផ្ទៀងផ្ទាត់ និងអនុម័ត:**
   - Admin ចូលកាន់ `/admin/orders`
   - ចុចលើវិក្កយបត្រដែលកំពុងរង់ចាំ
   - ពិនិត្យមើលរូបភាពបង្កាន់ដៃដែលសិស្សបាន Upload
   - ចុចប៊ូតុង **"អនុម័តការបង់ប្រាក់ (Approve)"**
   - ភ្លាមៗនោះ ប្រព័ន្ធនឹងប្តូរស្ថានភាពទៅជា `paid` និងបើកសិទ្ធិចូលរៀន (`enrollments`) ជូនសិស្សដោយស្វ័យប្រវត្តិ!
9. **សិស្សចាប់ផ្ដើមរៀន:** សិស្សចូលកាន់ `/student/my-courses` ឬ `/student/learn/{slug}` ដើម្បីរៀនមេរៀនវីដេអូទាំងអស់។

---

## ៧. លក្ខណៈពិសេសនៃ Video Learning Player

- **Distraction-Free Learning Interface:** ទំព័ររៀនពេញអេក្រង់ ស្អាត គ្មានការរំខាន
- **Curriculum Sidebar:** បង្ហាញជំពូក និងមេរៀនទាំងអស់ មានសញ្ញា Checkmark ពណ៌បៃតងបញ្ជាក់មេរៀនដែលបានរៀនរួច
- **Auto-Resume Playback:** ប្រព័ន្ធចងចាំវិនាទីចុងក្រោយដែលសិស្សបានទស្សនា និងបន្តចាក់ដោយស្វ័យប្រវត្តិនៅពេលបើកម្ដងទៀត
- **AJAX Progress Tracking:** រក្សាទុកវឌ្ឍនភាពសិក្សាស្វ័យប្រវត្តិតាមរយៈ Background AJAX API
- **Mark as Completed Toggle:** សិស្សអាចចុចកំណត់ថារៀនចប់ ឬមិនទាន់ចប់ដោយដៃផ្ទាល់
- **Free Preview Badges:** វគ្គបង់ប្រាក់អាចកំណត់មេរៀនខ្លះជា Free Preview ដើម្បីឱ្យសិស្សមើលសាកល្បងបានមុនពេលទិញ
- **Previous & Next Navigation:** ប៊ូតុងរុករកមេរៀនមុន និងមេរៀនបន្ទាប់យ៉ាងងាយស្រួល

---

## ៨. សុវត្ថិភាពប្រព័ន្ធ (Security Implementation)

- **SQL Injection Prevention:** ប្រើប្រាស់ PDO Prepared Statements 100% សម្រាប់គ្រប់ Queries ដែលមាន Input ពីអ្នកប្រើប្រាស់
- **Cross-Site Scripting (XSS) Prevention:** ទិន្នន័យទាំងអស់ត្រូវបាន Encode តាមរយៈ `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`
- **Cross-Site Request Forgery (CSRF) Protection:** ទម្រង់ Form ទាំងអស់ត្រូវបានការពារដោយ Secret CSRF Token (`csrf_field()`)
- **Secure Password Hashing:** ប្រើប្រាស់ `password_hash($pass, PASSWORD_DEFAULT)` និង `password_verify()`
- **Role-Based Access Control (RBAC):** មាន `AuthMiddleware` និង `AdminMiddleware` ការពារ Routes សំខាន់ៗ មិនឱ្យសិស្សធម្មតាចូលកាន់ `/admin` បានឡើយ
- **Secure File Uploads:** ផ្ទៀងផ្ទាត់ MIME Type ពិតប្រាកដតាមរយៈ `finfo`, កំណត់ទំហំអតិបរមា, និងផ្លាស់ប្តូរឈ្មោះឯកសារទៅជា Unique Filename ដើម្បីការពារ Remote Code Execution
- **Session Security:** ប្រើប្រាស់ `session.cookie_httponly = 1`, `session.use_only_cookies = 1`, និង `session_regenerate_id(true)` នៅពេល Login
- **Activity Audit Logging:** រាល់សកម្មភាពសំខាន់ៗរបស់ Admin (អនុម័តបង់ប្រាក់, បង្កើតវគ្គ, លុបមាតិកា) ត្រូវបានកត់ត្រាក្នុងតារាង `admin_activity_logs`

---

## ៩. តារាងត្រួតពិនិត្យមុខងារ (Feature Test Checklist)

- [x] **ទំព័រដើម (Home Page):** បង្ហាញ Hero, វគ្គសិក្សាឆ្នើម, វគ្គសិក្សាថ្មីៗ, ប្រភេទជំនាញ, តួលេខស្ថិតិ, មតិយោបល់សិស្ស, Footer
- [x] **ទំព័រវគ្គសិក្សា (Courses Catalog):** ស្វែងរកតាមឈ្មោះ, ច្រោះតាមផ្នែកជំនាញ, កម្រិត, តម្លៃ (Free/Paid), តម្រៀបតាមតម្លៃ/ពិន្ទុ, និង Pagination
- [x] **ទំព័រព័ត៌មានលម្អិតវគ្គសិក្សា (Course Details):** បង្ហាញ Curriculum Accordion, Instructor Bio, Reviews, Free Preview links, Buy button
- [x] **ប្រព័ន្ធ Authentication:** ចុះឈ្មោះសិស្ស, ចូលគណនី, ចាកចេញ, ភ្លេចពាក្យសម្ងាត់ និងកំណត់ពាក្យសម្ងាត់ថ្មី (Password Reset)
- [x] **Shopping Cart & Checkout:** បន្ថែមចូលកន្ត្រក, ដកចេញ, ប្រើកូដបញ្ចុះតម្លៃ (`KATTEB2026`), ជ្រើសរើស KHQR, បង្កើត Order
- [x] **ការទូទាត់ប្រាក់ & Upload បង្កាន់ដៃ:** បង្ហាញ KHQR code, លេខគណនី, ទម្រង់ Upload បង្កាន់ដៃ, ស្ថានភាពរង់ចាំផ្ទៀងផ្ទាត់
- [x] **Student Dashboard:** បង្ហាញស្ថិតិសិក្សា, ប៊ូតុងបន្តការរៀន, វគ្គសិក្សាដែលបានទិញ, ប្រវត្តិបញ្ជាទិញ
- [x] **Video Learning Interface:** ចាក់វីដេអូ, កត់ត្រាពេលវេលាស្វ័យប្រវត្តិ, Mark lesson as complete, មេរៀនមុន/បន្ទាប់, Sidebar curriculum
- [x] **Admin Dashboard:** បង្ហាញស្ថិតិពិតពី Database (សិស្ស, វគ្គ, មេរៀន, Orders, ចំណូល, វិក្កយបត្ររង់ចាំ), តារាង Bar Chart ចំណូលប្រចាំខែ
- [x] **Course Management:** បង្កើតវគ្គសិក្សា, កែប្រែ, លុប, Upload Thumbnail, Featured/Popular toggle
- [x] **Section & Lesson Management:** បង្កើតជំពូក, បន្ថែមមេរៀនវីដេអូ, កំណត់រយៈពេល, Free Preview toggle
- [x] **Category Management:** បង្កើត, កែប្រែ, លុប (មានការការពារមិនឱ្យលុបបើមានវគ្គសិក្សាកំពុងប្រើប្រាស់)
- [x] **Order & Payment Moderation:** ពិនិត្យមើលបង្កាន់ដៃរូបភាព, ប៊ូតុង Approve បើកសិទ្ធិចូលរៀន, ប៊ូតុង Reject ជាមួយមូលហេតុ
- [x] **Coupon Management:** បង្កើតកូដបញ្ចុះតម្លៃ, កំណត់ភាគរយ, ថ្ងៃផុតកំណត់, និងចំនួនកំណត់ប្រើប្រាស់
- [x] **Review Moderation:** ពិនិត្យមតិសិស្ស, អនុម័ត/លាក់មតិ, លុបមតិមិនសមរម្យ
- [x] **Website Settings:** កែប្រែឈ្មោះគេហទំព័រ, ពាក្យស្លោក, លេខទូរស័ព្ទ, គណនី ABA/Wing/ACLEDA, រូបភាព KHQR, FAQ
- [x] **Admin Audit Logs:** កត់ត្រា IP, ឈ្មោះ Admin, សកម្មភាព និងពេលវេលា
- [x] **Static Pages:** អំពីយើង (About Us), ទំនាក់ទំនង (Contact Us ជាមួយទម្រង់ផ្ញើសាររក្សាទុកក្នុង DB), FAQ, Privacy, Terms, 404 Error page
