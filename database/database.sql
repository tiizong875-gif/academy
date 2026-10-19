-- ============================================================
-- ក្តឹបកូដ Academy — Online Video Learning Platform Database
-- Database: katteb_academy
-- Character Set: utf8mb4 (Full Khmer & Emoji support)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `katteb_academy` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `katteb_academy`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(30) NULL,
    `role` ENUM('student', 'instructor', 'admin') NOT NULL DEFAULT 'student',
    `avatar` VARCHAR(255) NULL,
    `headline` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Categories table
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `name_kh` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `icon` VARCHAR(60) DEFAULT 'bi-code-slash',
    `image` VARCHAR(255) NULL,
    `description` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_categories_slug` (`slug`),
    INDEX `idx_categories_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Courses table
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT UNSIGNED NOT NULL,
    `instructor_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `title_kh` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `short_description` VARCHAR(500) NULL,
    `description` LONGTEXT NULL,
    `requirements` TEXT NULL,
    `target_audience` TEXT NULL,
    `learning_objectives` TEXT NULL,
    `level` ENUM('all', 'beginner', 'intermediate', 'advanced') NOT NULL DEFAULT 'beginner',
    `language` VARCHAR(50) NOT NULL DEFAULT 'ភាសាខ្មែរ (Khmer)',
    `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `discount_price` DECIMAL(10, 2) NULL DEFAULT NULL,
    `is_free` TINYINT(1) NOT NULL DEFAULT 0,
    `thumbnail` VARCHAR(255) NULL,
    `video_preview_url` VARCHAR(255) NULL,
    `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
    `is_popular` TINYINT(1) NOT NULL DEFAULT 0,
    `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'published',
    `duration_minutes` INT NOT NULL DEFAULT 0,
    `total_lessons` INT NOT NULL DEFAULT 0,
    `average_rating` DECIMAL(3, 2) NOT NULL DEFAULT 5.00,
    `review_count` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_courses_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_courses_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    INDEX `idx_courses_slug` (`slug`),
    INDEX `idx_courses_status` (`status`),
    INDEX `idx_courses_featured` (`is_featured`),
    INDEX `idx_courses_popular` (`is_popular`),
    INDEX `idx_courses_level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Course Sections table
DROP TABLE IF EXISTS `course_sections`;
CREATE TABLE `course_sections` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_sections_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    INDEX `idx_sections_course_order` (`course_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Lessons table
DROP TABLE IF EXISTS `lessons`;
CREATE TABLE `lessons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `section_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `video_type` ENUM('url', 'upload', 'youtube', 'vimeo') NOT NULL DEFAULT 'url',
    `video_url` VARCHAR(500) NULL,
    `video_file` VARCHAR(255) NULL,
    `duration_seconds` INT NOT NULL DEFAULT 0,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_free_preview` TINYINT(1) NOT NULL DEFAULT 0,
    `is_published` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_lessons_section` FOREIGN KEY (`section_id`) REFERENCES `course_sections` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lessons_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    INDEX `idx_lessons_course` (`course_id`),
    INDEX `idx_lessons_section_order` (`section_id`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Enrollments table
DROP TABLE IF EXISTS `enrollments`;
CREATE TABLE `enrollments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `order_id` INT UNSIGNED NULL,
    `enrolled_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    `status` ENUM('active', 'expired', 'revoked') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_enrollments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_enrollments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_user_course` (`user_id`, `course_id`),
    INDEX `idx_enrollments_user` (`user_id`),
    INDEX `idx_enrollments_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Lesson Progress table
DROP TABLE IF EXISTS `lesson_progress`;
CREATE TABLE `lesson_progress` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `lesson_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
    `last_watched_second` INT NOT NULL DEFAULT 0,
    `completed_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_progress_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_user_lesson` (`user_id`, `lesson_id`),
    INDEX `idx_progress_user_course` (`user_id`, `course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Payment Methods table
DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE `payment_methods` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `account_name` VARCHAR(150) NULL,
    `account_number` VARCHAR(100) NULL,
    `qr_image` VARCHAR(255) NULL,
    `instructions` TEXT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Orders table
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(50) NOT NULL UNIQUE,
    `user_id` INT UNSIGNED NOT NULL,
    `subtotal` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `coupon_code` VARCHAR(50) NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `payment_method_id` INT UNSIGNED NULL,
    `payment_status` ENUM('pending', 'paid', 'rejected', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending',
    `notes` TEXT NULL,
    `admin_notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_orders_payment_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
    INDEX `idx_orders_user` (`user_id`),
    INDEX `idx_orders_status` (`payment_status`),
    INDEX `idx_orders_number` (`order_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Order Items table
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE RESTRICT,
    INDEX `idx_order_items_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Payments table
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `payment_method` VARCHAR(100) NOT NULL,
    `transaction_reference` VARCHAR(100) NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `receipt_image` VARCHAR(255) NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_id` INT UNSIGNED NULL,
    `verified_at` TIMESTAMP NULL DEFAULT NULL,
    `rejection_reason` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_payments_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_payments_order` (`order_id`),
    INDEX `idx_payments_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Coupons table
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    `discount_value` DECIMAL(10, 2) NOT NULL,
    `min_order_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `max_discount_amount` DECIMAL(10, 2) NULL DEFAULT NULL,
    `usage_limit` INT NOT NULL DEFAULT 100,
    `usage_count` INT NOT NULL DEFAULT 0,
    `start_date` DATE NULL,
    `expiry_date` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_coupons_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Coupon Usages table
DROP TABLE IF EXISTS `coupon_usages`;
CREATE TABLE `coupon_usages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `coupon_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `order_id` INT UNSIGNED NOT NULL,
    `discount_amount` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_coupon_usages_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_coupon_usages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_coupon_usages_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Reviews table
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `comment` TEXT NOT NULL,
    `is_approved` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reviews_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    INDEX `idx_reviews_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Wishlists table
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `course_id` INT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_wishlists_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlists_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `uk_wishlist_user_course` (`user_id`, `course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Contact Messages table
DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE `contact_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `replied_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. FAQs table
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(255) NOT NULL,
    `question_kh` VARCHAR(255) NOT NULL,
    `answer` TEXT NOT NULL,
    `answer_kh` TEXT NOT NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Site Settings table
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` LONGTEXT NULL,
    `group_name` VARCHAR(50) NOT NULL DEFAULT 'general',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Admin Activity Logs table
DROP TABLE IF EXISTS `admin_activity_logs`;
CREATE TABLE `admin_activity_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `target_type` VARCHAR(100) NULL,
    `target_id` INT UNSIGNED NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(50) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_logs_admin` (`admin_id`),
    INDEX `idx_logs_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Password Resets table
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `idx_resets_email` (`email`),
    INDEX `idx_resets_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

-- 1. Users
-- Default Admin Password: Admin@12345
-- Default Student Password: Student@12345
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `headline`, `bio`, `status`) VALUES
(1, 'Admin ក្តឹបកូដ', 'admin@kattebcode.com', '$2y$10$znT8uhs3IvYwHxfpP0yl6.GH.0I3qI8hTecHS4mu1yNus9SYfQTti', '012 345 678', 'admin', 'Lead Architect & Platform Admin', 'គ្រប់គ្រងប្រព័ន្ធនិងការអភិវឌ្ឍវេទិកាសិក្សាអនឡាញ ក្តឹបកូដ Academy', 'active'),
(2, 'គ្រូ សុខ វិសាល', 'visal@kattebcode.com', '$2y$10$znT8uhs3IvYwHxfpP0yl6.GH.0I3qI8hTecHS4mu1yNus9SYfQTti', '010 888 999', 'instructor', 'Senior Web Developer & Instructor', 'បទពិសោធន៍ជាង ៨ឆ្នាំក្នុង Full-Stack Web Development (PHP, JavaScript, Python, Vue, React)។ ស្រឡាញ់ការចែករំលែកចំណេះដឹងដល់កូនខ្មែរ។', 'active'),
(3, 'សិស្ស សុវណ្ណរត្ន័', 'student@kattebcode.com', '$2y$10$z1PLOpetV1YwBQmtY/Tv/.LY/XtOouu6bMJdm28tcPR6R0vUQ6Wpq', '096 111 222', 'student', 'Student & Aspiring Programmer', 'រៀនកូដដើម្បីបង្កើតគេហទំព័រនិងកម្មវិធីទូរស័ព្ទដោយខ្លួនឯង', 'active');

-- 2. Categories
INSERT INTO `categories` (`id`, `name`, `name_kh`, `slug`, `icon`, `image`, `description`, `sort_order`) VALUES
(1, 'Web Development', 'ការបង្កើតគេហទំព័រ (Web Dev)', 'web-development', 'bi-code-slash', 'category-web.jpg', 'រៀនបង្កើតគេហទំព័រពេញលេញជាមួយ HTML5, CSS3, JavaScript, PHP, MySQL', 1),
(2, 'Backend Programming', 'កម្មវិធីប្រព័ន្ធក្រោយ (Backend)', 'backend-programming', 'bi-server', 'category-backend.jpg', 'ស្វែងយល់ស៊ីជម្រៅលើ PHP, API, Database Design, Security & Architecture', 2),
(3, 'Frontend Frameworks', 'បច្ចេកវិទ្យាផ្នែកខាងមុខ (Frontend)', 'frontend-frameworks', 'bi-window-sidebar', 'category-frontend.jpg', 'បង្កើត UI ដ៏ស្រស់ស្អាតនិងមាន Dynamic ខ្ពស់ជាមួយ modern JS & CSS frameworks', 3),
(4, 'Mobile App Development', 'កម្មវិធីទូរស័ព្ទ (Mobile Apps)', 'mobile-development', 'bi-phone', 'category-mobile.jpg', 'រៀនបង្កើត Mobile App សម្រាប់ iOS និង Android', 4),
(5, 'Database & DevOps', 'ទិន្នន័យ & DevOps', 'database-devops', 'bi-database-fill-gear', 'category-database.jpg', 'គ្រប់គ្រង MySQL, Linux Server, Git, Deployment, Docker', 5);

-- 3. Payment Methods
INSERT INTO `payment_methods` (`id`, `name`, `code`, `account_name`, `account_number`, `qr_image`, `instructions`, `is_active`, `sort_order`) VALUES
(1, 'ABA Bank (KHQR)', 'aba_khqr', 'KATTEB CODE ACADEMY', '001 234 567 (USD)', 'aba_qr.png', 'សូមស្កេន KHQR កូដតាមរយៈ ABA Mobile ឬធនាគារនានាក្នុងប្រទេសកម្ពុជា។ បន្ទាប់ពីផ្ទេរប្រាក់រួច សូមថតរូបបង្កាន់ដៃ (Screenshot) រួច Upload ដើម្បីឱ្យរដ្ឋបាលផ្ទៀងផ្ទាត់។', 1, 1),
(2, 'Wing Bank / Wing Money', 'wing', 'KATTEB CODE ACADEMY', '096 123 4567', 'wing_qr.png', 'ផ្ទេរប្រាក់តាម Wing App ឬភ្នាក់ងារ Wing មកកាន់លេខគណនីខាងលើ រួចបញ្ចូលលេខកូដប្រតិបត្តិការ (Transaction ID) និង Upload វិក្កយបត្រ។', 1, 2),
(3, 'ACLEDA Bank (KHQR)', 'acleda', 'KATTEB CODE ACADEMY', '012-345678-1-1', 'acleda_qr.png', 'ទូទាត់តាម ACLEDA mobile ឬ Bakong KHQR។ ផ្ញើរូបភាពបង្កាន់ដៃបញ្ជាក់ការបង់ប្រាក់។', 1, 3);

-- 4. Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('site_name', 'ក្តឹបកូដ Academy', 'general'),
('site_slogan', 'រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត', 'general'),
('site_email', 'contact@kattebcode.com', 'contact'),
('site_phone', '+855 12 345 678 / +855 96 111 222', 'contact'),
('site_address', 'អគារបច្ចេកវិទ្យា ក្តឹបកូដ, មហាវិថីសហព័ន្ធរុស្ស៊ី, រាជធានីភ្នំពេញ, កម្ពុជា', 'contact'),
('site_logo', 'assets/images/logo.png', 'general'),
('site_favicon', 'assets/images/favicon.ico', 'general'),
('facebook_url', 'https://facebook.com/kattebcode', 'social'),
('telegram_url', 'https://t.me/kattebcode', 'social'),
('youtube_url', 'https://youtube.com/@kattebcode', 'social'),
('tiktok_url', 'https://tiktok.com/@kattebcode', 'social'),
('currency_symbol', '$', 'payment'),
('manual_payment_enabled', '1', 'payment');

-- 5. Courses
INSERT INTO `courses` (
    `id`, `category_id`, `instructor_id`, `title`, `title_kh`, `slug`,
    `short_description`, `description`, `requirements`, `target_audience`, `learning_objectives`,
    `level`, `language`, `price`, `discount_price`, `is_free`, `thumbnail`, `video_preview_url`,
    `is_featured`, `is_popular`, `status`, `duration_minutes`, `total_lessons`, `average_rating`, `review_count`
) VALUES
(
    1, 1, 2,
    'Modern Full-Stack Web Development with PHP & MySQL',
    'វគ្គសិក្សាពេញលេញ Full-Stack Web Development ជាមួយ PHP & MySQL ពីកម្រិតដំបូងដល់កម្រិតខ្ពស់',
    'full-stack-web-php-mysql',
    'ចាប់ផ្ដើមពីកម្រិតសូន្យ រៀនបង្កើតគេហទំព័រពិតប្រាកដដោយប្រើ HTML5, CSS3, JavaScript, Bootstrap 5, PHP 8+ និង MySQL PDO។',
    '<h3>អំពីវគ្គសិក្សានេះ</h3><p>វគ្គសិក្សា <strong>Full-Stack Web Development ជាមួយ PHP & MySQL</strong> ត្រូវបានរចនាឡើងយ៉ាងពិសេសសម្រាប់សិស្ស-និស្សិត និងអ្នកដែលចង់ក្លាយជា Web Developer អាជីព។ អ្នកនឹងបានអនុវត្តការសរសេរកូដផ្ទាល់ដៃ (Hands-on Projects) តាំងពីបង្កើតទំព័រ Interface រហូតដល់រៀបចំ Database ស្មុគស្មាញ និងដាក់ឱ្យដំណើរការលើ Server ពិតប្រាកដ។</p><h4>អ្វីដែលអ្នកនឹងរៀន៖</h4><ul><li>មូលដ្ឋានគ្រឹះរឹងមាំនៃ HTML5, Modern CSS3, Flexbox, Grid និង Bootstrap 5</li><li>JavaScript សម័យទំនើប ES6+ និងការប្រើប្រាស់ Fetch API សម្រាប់ AJAX</li><li>PHP 8+ Object-Oriented Programming (OOP) និង MVC Design Pattern</li><li>MySQL Database Architecture, Prepared Statements និង Security (XSS, CSRF, SQL Injection Protection)</li><li>ការបង្កើតគម្រោងជាក់ស្ដែង e-Commerce ឬ Platform ដូចជាវេទិកានេះ</li></ul>',
    'កុំព្យូទ័រ (Windows, Mac ឬ Linux) ដែលមានតម្លើងកម្មវិធី XAMPP និង VS Code\nមិនទាមទារបទពិសោធន៍សរសេរកូដពីមុនទេ មានតែការតាំងចិត្តនិងចង់ចេះចង់ដឹង',
    'សិស្ស និស្សិតព័ត៌មានវិទ្យា (IT)\nអ្នកចង់ផ្លាស់ប្តូរអាជីពមកកាន់វិស័យ Programming\nអ្នកសរសេរកូដដំបូងដែលចង់យល់ពី Full-Stack Web Development',
    'បង្កើតគេហទំព័រ responsive ដែលដំណើរការលើទូរស័ព្ទនិងកុំព្យូទ័រ\nចេះរៀបចំ Database ជាមួយ MySQL និងការពារប្រព័ន្ធឱ្យមានសុវត្ថិភាព\nយល់ច្បាស់ពី MVC Architecture និងការសរសេរកូដស្អាត (Clean Code)\nអាចបង្កើតគម្រោងជាក់ស្ដែងសម្រាប់ដាក់ក្នុង CV រកការងារបាន',
    'beginner', 'ភាសាខ្មែរ (Khmer)', 49.00, 29.00, 0,
    'course-fullstack.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    1, 1, 'published', 480, 10, 4.90, 18
),
(
    2, 2, 2,
    'Mastering PHP 8 & Clean MVC Architecture from Scratch',
    'ជំនាញស៊ីជម្រៅ PHP 8 & ការកសាង MVC Framework ដោយខ្លួនឯង',
    'mastering-php-8-clean-mvc',
    'ស្វែងយល់ពី Object-Oriented Programming (OOP) កម្រិតខ្ពស់, Design Patterns និងការបង្កើត MVC Engine ដោយផ្ទាល់។',
    '<p>វគ្គនេះផ្តោតលើការកសាងស្ថាបត្យកម្មកូដឱ្យមានស្តង់ដារ សុវត្ថិភាពខ្ពស់ និងងាយស្រួលពង្រីក (Scalable)។ ស័ក្តិសមបំផុតសម្រាប់អ្នកដែលមានមូលដ្ឋាន PHP រួចហើយ ហើយចង់ឡើងកម្រិតជំនាញឱ្យក្លាយជា Senior Developer។</p>',
    'យល់ដឹងពី PHP មូលដ្ឋាន និង syntax គ្រឹះ\nមានកម្មវិធី XAMPP ឬ PHP 8.x',
    'Web Developer ដែលចង់ឡើងកម្រិតស្ថាបត្យកម្មកូដ\nនិស្សិត IT ដែលចង់យល់ពីរបៀបដំណើរការខាងក្នុងនៃ Frameworks (ដូចជា Laravel)',
    'សរសេរ OOP PHP ស្ទាត់ជំនាញ\nយល់ដឹងពី Router, Controller, Model, Middleware\nការពារចន្លោះប្រហោងសុវត្ថិភាពតាម OWASP Top 10',
    'intermediate', 'ភាសាខ្មែរ (Khmer)', 39.00, 19.00, 0,
    'course-php-mvc.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    1, 1, 'published', 360, 8, 4.85, 12
),
(
    3, 1, 2,
    'HTML5, Modern CSS3 & Responsive UI Design for Beginners',
    'មូលដ្ឋានគ្រឹះ HTML5, CSS3 & ការរចនា UI ស្អាតទាន់សម័យ',
    'html5-css3-responsive-ui',
    'រៀនរចនាគេហទំព័រឱ្យស្រស់ស្អាត Responsive ដំណើរការល្អលើគ្រប់ទំហំអេក្រង់។ វគ្គសិក្សាឥតគិតថ្លៃ (Free)!',
    '<p>វគ្គសិក្សាឥតគិតថ្លៃនេះត្រូវបានបង្កើតឡើងសម្រាប់បងប្អូនដែលទើបចាប់ផ្ដើមដំបូងបំផុត។ អ្នកនឹងរៀនពីរបៀបបង្កើតរចនាសម្ព័ន្ធទំព័រគេហទំព័រ និងការតុបតែងឱ្យមានភាពទាក់ទាញតាមស្តង់ដារសម័យថ្មី។</p>',
    'កុំព្យូទ័រនិងកម្មវិធី Browser (Chrome, Edge)',
    'អ្នកទើបចាប់ផ្ដើមរៀនដំបូងបំផុត (Complete Beginner)',
    'ចេះបង្កើតទំព័រ Website ដំបូងដោយដៃខ្លួនឯង\nយល់ច្បាស់ពី Flexbox, Grid និង Media Queries',
    'beginner', 'ភាសាខ្មែរ (Khmer)', 0.00, NULL, 1,
    'course-html-css.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    1, 0, 'published', 180, 5, 5.00, 35
),
(
    4, 5, 2,
    'Practical MySQL Database Design & Optimization',
    'ការរចនាទិន្នន័យ MySQL & ការបង្កើនល្បឿន Query ជាក់ស្ដែង',
    'mysql-database-design-optimization',
    'រចនា Database ឱ្យត្រឹមត្រូវតាម Normalization, Foreign Keys, Indexes និងការសរសេរ Query ស្មុគស្មាញឱ្យរត់លឿន។',
    '<p>Database គឺជាបេះដូងនៃប្រព័ន្ធកម្មវិធីគ្រប់ប្រភេទ។ វគ្គនេះនឹងបង្រៀនអ្នកពីរបៀបវិភាគ តារាងទិន្នន័យ និងទំនាក់ទំនង រួមទាំងការប្រើប្រាស់ Transaction និង Stored Procedures។</p>',
    'ចំណេះដឹងកុំព្យូទ័រទូទៅ',
    'អ្នកចង់ក្លាយជា Database Administrator ឬ Backend Developer',
    'រចនា Database ខ្នាតធំដោយគ្មានទិន្នន័យជាន់គ្នា (Redundancy)\nបង្កើនល្បឿន Query តាមរយៈ Indexing ត្រឹមត្រូវ',
    'intermediate', 'ភាសាខ្មែរ (Khmer)', 35.00, 25.00, 0,
    'course-mysql.jpg', 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    0, 1, 'published', 300, 6, 4.75, 8
);

-- 6. Course Sections
INSERT INTO `course_sections` (`id`, `course_id`, `title`, `sort_order`) VALUES
(1, 1, 'ផ្នែកទី ១៖ សេចក្ដីផ្ដើមនិងការរៀបចំកម្មវិធីអភិវឌ្ឍន៍ (Introduction & Environment Setup)', 1),
(2, 1, 'ផ្នែកទី ២៖ មូលដ្ឋានគ្រឹះ HTML5 & CSS3 សម័យថ្មី', 2),
(3, 1, 'ផ្នែកទី ៣៖ ការប្រើប្រាស់ PHP 8+ និង MySQL PDO Database', 3),
(4, 1, 'ផ្នែកទី ៤៖ ការកសាងគម្រោង e-Commerce & Deployment', 4),
(5, 2, 'ផ្នែកទី ១៖ PHP 8 Object-Oriented Programming (OOP) Deep Dive', 1),
(6, 2, 'ផ្នែកទី ២៖ ការបង្កើត Custom Router & Request Handler', 2),
(7, 2, 'ផ្នែកទី ៣៖ Database Abstraction Layer & Middleware', 3),
(8, 3, 'ផ្នែកទី ១៖ មូលដ្ឋានគ្រឹះ HTML5 Tags & Document Structure', 1),
(9, 3, 'ផ្នែកទី ២៖ CSS Selectors, Box Model & Responsive Design', 2);

-- 7. Lessons
INSERT INTO `lessons` (
    `id`, `section_id`, `course_id`, `title`, `slug`, `description`,
    `video_type`, `video_url`, `duration_seconds`, `sort_order`, `is_free_preview`, `is_published`
) VALUES
(1, 1, 1, 'មេរៀនទី ១.១៖ ស្វាគមន៍មកកាន់វគ្គសិក្សា និង Roadmap នៃការរៀន', 'welcome-course-roadmap', 'ទិដ្ឋភាពទូទៅនៃវគ្គសិក្សា អ្វីដែលត្រូវរៀន និងរបៀបរៀបចំផែនការសិក្សាឱ្យទទួលបានជោគជ័យ។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 420, 1, 1, 1),
(2, 1, 1, 'មេរៀនទី ១.២៖ ការតម្លើង XAMPP, VS Code និង Extensions សំខាន់ៗ', 'install-xampp-vscode', 'ណែនាំជំហានតម្លើងកម្មវិធី Apache, MySQL និងការកំណត់ Editor ឱ្យមានភាពងាយស្រួល។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4', 680, 2, 1, 1),
(3, 2, 1, 'មេរៀនទី ២.១៖ រចនាសម្ព័ន្ធទំព័រ Semantic HTML5 & SEO Elements', 'semantic-html5-elements', 'ស្វែងយល់ពី Header, Nav, Main, Section, Article, Aside, Footer និង Meta tags សម្រាប់ SEO។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4', 850, 1, 0, 1),
(4, 2, 1, 'មេរៀនទី ២.២៖ Layout ទំនើបជាមួយ Flexbox & CSS Grid', 'modern-css-flexbox-grid', 'រៀនបង្កើត Grid System និង UI Components ឱ្យមានភាពបត់បែនលើគ្រប់ឧបករណ៍។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4', 920, 2, 0, 1),
(5, 3, 1, 'មេរៀនទី ៣.១៖ មូលដ្ឋានគ្រឹះ PHP 8 Syntax, Variables & Functions', 'php-8-basics-variables-functions', 'ចាប់ផ្ដើមសរសេរ PHP ដំបូង ស្វែងយល់ពី Data types, Loops, Conditions និង Functions។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4', 1100, 1, 0, 1),
(6, 3, 1, 'មេរៀនទី ៣.២៖ ការតភ្ជាប់ MySQL ជាមួយ PDO & Prepared Statements', 'mysql-pdo-prepared-statements', 'រៀនតភ្ជាប់ Database ដោយសុវត្ថិភាព និងការពារការវាយប្រហារតាម SQL Injection។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyBlazes.mp4', 1250, 2, 0, 1),
(7, 3, 1, 'មេរៀនទី ៣.៣៖ ការគ្រប់គ្រង Session, Cookie និង User Authentication', 'php-sessions-auth-workflow', 'បង្កើតប្រព័ន្ធ Login, Logout, Password Hashing និង Role-based access control។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4', 1400, 3, 0, 1),
(8, 4, 1, 'មេរៀនទី ៤.១៖ បង្កើតប្រព័ន្ធ Shopping Cart & Checkout', 'shopping-cart-checkout-system', 'អនុវត្តការសរសេរ Session-based Cart និងការបង្កើត Order ចូលទៅកាន់ Database។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4', 1500, 1, 0, 1),
(9, 4, 1, 'មេរៀនទី ៤.២៖ ការ Upload វិក្កយបត្រ និង Manual Payment Verification', 'payment-receipt-upload-verification', 'បង្កើតទម្រង់ Upload រូបភាពវិក្កយបត្រដោយមានការ Validation និងការយល់ព្រមពី Admin។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackSeeTheWorld.mp4', 1350, 2, 0, 1),
(10, 4, 1, 'មេរៀនទី ៤.៣៖ ការ Deploy Website ទៅកាន់ Live Server', 'deploy-website-live-hosting', 'រៀបចំ Domain, Hosting, MySQL Export/Import និងការកំណត់ SSL HTTPS។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4', 1600, 3, 0, 1),
(11, 5, 2, 'មេរៀនទី ១.១៖ Classes, Objects, Inheritance & Interfaces', 'oop-classes-objects-interfaces', 'ការយល់ដឹងស៊ីជម្រៅលើ OOP ក្នុង PHP 8 ជាមួយ Typed properties និង Constructor Promotion។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4', 1200, 1, 1, 1),
(12, 5, 2, 'មេរៀនទី ១.២៖ Namespaces, Autoloading & PSR-4 Standard', 'psr4-namespaces-autoloading', 'របៀបរៀបចំ Folder និង Class តាមស្តង់ដារពិភពលោក PSR-4។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4', 980, 2, 0, 1),
(13, 8, 3, 'មេរៀនទី ១.១៖ សេចក្ដីណែនាំអំពី HTML5 និង Syntax ដំបូង', 'intro-html5-syntax', 'ស្វែងយល់ពីរបៀបដែលគេហទំព័រដំណើរការ និងការសរសេរកូដ HTML ដំបូងបង្អស់។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4', 700, 1, 1, 1),
(14, 9, 3, 'មេរៀនទី ២.១៖ ការតុបតែងទំព័រជាមួយ CSS3 និង Color Schemes', 'styling-css3-colors-fonts', 'ការកំណត់ពណ៌ អក្សរខ្មែរ Kantumruy Pro និងគម្លាត Margin, Padding។', 'url', 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4', 850, 1, 1, 1);

-- 8. Enrollments & Sample Orders
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `subtotal`, `discount_amount`, `coupon_code`, `total_amount`, `payment_method_id`, `payment_status`, `notes`) VALUES
(1, 'ORD-2026-0001', 3, 29.00, 0.00, NULL, 29.00, 1, 'paid', 'បានបង់ប្រាក់តាម ABA KHQR រួចរាល់');

INSERT INTO `order_items` (`id`, `order_id`, `course_id`, `price`) VALUES
(1, 1, 1, 29.00);

INSERT INTO `payments` (`id`, `order_id`, `user_id`, `payment_method`, `transaction_reference`, `amount`, `receipt_image`, `status`, `admin_id`, `verified_at`) VALUES
(1, 1, 3, 'ABA Bank (KHQR)', 'TXN-ABA-98765432', 29.00, 'receipt-demo.jpg', 'approved', 1, CURRENT_TIMESTAMP);

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `order_id`, `status`) VALUES
(1, 3, 1, 1, 'active'),
(2, 3, 3, NULL, 'active');

INSERT INTO `lesson_progress` (`user_id`, `lesson_id`, `course_id`, `is_completed`, `last_watched_second`, `completed_at`) VALUES
(3, 1, 1, 1, 420, CURRENT_TIMESTAMP),
(3, 2, 1, 0, 310, NULL);

-- 9. Coupons
INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `min_order_amount`, `usage_limit`, `usage_count`, `start_date`, `expiry_date`, `is_active`) VALUES
(1, 'KATTEB2026', 'percentage', 20.00, 20.00, 500, 1, '2026-01-01', '2026-12-31', 1),
(2, 'WELCOME5', 'fixed', 5.00, 25.00, 200, 0, '2026-01-01', '2026-12-31', 1);

-- 10. Reviews
INSERT INTO `reviews` (`id`, `user_id`, `course_id`, `rating`, `comment`, `is_approved`) VALUES
(1, 3, 1, 5, 'វគ្គសិក្សានេះល្អខ្លាំងណាស់! គ្រូបង្រៀនបានក្បោះក្បាយ ច្បាស់លាស់ និងមានគម្រោងអនុវត្តផ្ទាល់ដៃ។ ខ្ញុំអាចយល់ពី PHP និង MySQL បានយ៉ាងរហ័ស។', 1);

-- 11. FAQs
INSERT INTO `faqs` (`id`, `question`, `question_kh`, `answer`, `answer_kh`, `category`, `sort_order`, `is_active`) VALUES
(1, 'How can I enroll in a course?', 'តើខ្ញុំអាចចុះឈ្មោះចូលរៀនវគ្គសិក្សាដោយរបៀបណា?', 'You can register an account, browse our courses, click on any course, and proceed through our checkout process. For paid courses, submit your payment receipt and access will be granted upon verification.', 'លោកអ្នកគ្រាន់តែចុះឈ្មោះបង្កើតគណនី រួចជ្រើសរើសវគ្គសិក្សាដែលពេញចិត្ត។ សម្រាប់វគ្គបង់ប្រាក់ លោកអ្នកអាចស្កេន KHQR បង់ប្រាក់ រួច Upload បង្កាន់ដៃ នោះរដ្ឋបាលនឹងផ្ទៀងផ្ទាត់បើកសិទ្ធិចូលរៀនភ្លាមៗ។', 'enrollment', 1, 1),
(2, 'Can I learn on mobile devices?', 'តើខ្ញុំអាចរៀនលើទូរស័ព្ទដៃ ឬ Tablet បានទេ?', 'Yes! ក្តឹបកូដ Academy is fully responsive and optimized for smartphones, tablets, laptops, and desktop computers.', 'បាទ/ចាស! គេហទំព័រ ក្តឹបកូដ Academy ត្រូវបានរចនាឡើងយ៉ាងទំនើប និងអាចដំណើរការបានយ៉ាងរលូនលើទូរស័ព្ទដៃ ថេប្លេត និងកុំព្យូទ័រគ្រប់ប្រភេទ។', 'general', 2, 1),
(3, 'Are there any free courses available?', 'តើមានវគ្គសិក្សាឥតគិតថ្លៃដែរឬទេ?', 'Yes, we provide several free beginner-friendly courses, and paid courses also have free preview lessons so you can try before purchasing.', 'មាន! យើងខ្ញុំមានវគ្គសិក្សា Free ជាច្រើនសម្រាប់អ្នកចាប់ផ្ដើមដំបូង ហើយវគ្គបង់ប្រាក់នីមួយៗក៏មានមេរៀនមើលសាកល្បងដោយឥតគិតថ្លៃ (Free Preview) ផងដែរ។', 'courses', 3, 1),
(4, 'How long do I have access to the courses?', 'តើសិទ្ធិចូលរៀនមានកំណត់រយៈពេលដែរឬទេ?', 'Once enrolled, you enjoy lifetime access to the course content and future updates.', 'ក្រោយពេលចុះឈ្មោះរួច លោកអ្នកទទួលបានសិទ្ធិចូលរៀនពេញមួយជីវិត (Lifetime Access) រួមទាំងការ Update មេរៀនថ្មីៗនាពេលអនាគត។', 'general', 4, 1);

-- 12. Sample Admin Activity Logs
INSERT INTO `admin_activity_logs` (`admin_id`, `action`, `target_type`, `target_id`, `details`, `ip_address`) VALUES
(1, 'APPROVE_PAYMENT', 'Payment', 1, 'បានផ្ទៀងផ្ទាត់និងអនុម័តការបង់ប្រាក់សម្រាប់វិក្កយបត្រ ORD-2026-0001 ចំនួន $29.00', '127.0.0.1'),
(1, 'PUBLISH_COURSE', 'Course', 1, 'បានផ្សព្វផ្សាយវគ្គសិក្សា Modern Full-Stack Web Development', '127.0.0.1');
