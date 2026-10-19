<?php
/**
 * Asset generator for placeholders, logos and QR codes
 */

$imgDir = __DIR__ . '/../public/assets/images';
$thumbDir = __DIR__ . '/../public/uploads/thumbnails';
$qrDir = __DIR__ . '/../public/uploads/qr';
$receiptDir = __DIR__ . '/../public/uploads/receipts';

@mkdir($imgDir, 0755, true);
@mkdir($thumbDir, 0755, true);
@mkdir($qrDir, 0755, true);
@mkdir($receiptDir, 0755, true);

// 1. Logo
$logo = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 80" width="350" height="80">
  <rect width="350" height="80" fill="#0F172A" rx="10"/>
  <rect x="12" y="12" width="56" height="56" rx="12" fill="#22C55E"/>
  <path d="M30 28 L22 40 L30 52 M50 28 L58 40 L50 52 M44 26 L36 54" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
  <text x="80" y="44" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="bold" font-size="24">ក្តឹបកូដ</text>
  <text x="175" y="44" fill="#22C55E" font-family="system-ui, sans-serif" font-weight="bold" font-size="20">ACADEMY</text>
  <text x="82" y="62" fill="#94A3B8" font-family="system-ui, sans-serif" font-size="11">រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត</text>
</svg>';
file_put_contents($imgDir . '/logo.svg', $logo);
file_put_contents($imgDir . '/logo.png', $logo);

// 2. Default Avatar
$avatar = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
  <circle cx="60" cy="60" r="60" fill="#1E293B"/>
  <circle cx="60" cy="45" r="22" fill="#94A3B8"/>
  <path d="M24 102 C28 78 44 72 60 72 C76 72 92 78 96 102 Z" fill="#94A3B8"/>
</svg>';
file_put_contents($imgDir . '/default-avatar.svg', $avatar);
file_put_contents($imgDir . '/default-avatar.png', $avatar);

// 3. QR Codes
function makeQr($name, $color, $outFile) {
    $qr = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 400" width="320" height="400">
      <rect width="320" height="400" fill="#FFFFFF" rx="16" stroke="#E2E8F0" stroke-width="4"/>
      <rect x="20" y="20" width="280" height="60" rx="8" fill="' . $color . '"/>
      <text x="160" y="56" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="bold" font-size="18" text-anchor="middle">' . $name . ' KHQR</text>
      <rect x="40" y="100" width="240" height="240" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="2" rx="8"/>
      <g fill="#0F172A">
        <rect x="60" y="120" width="50" height="50" rx="4"/>
        <rect x="68" y="128" width="34" height="34" fill="#FFFFFF" rx="2"/>
        <rect x="76" y="136" width="18" height="18"/>
        <rect x="210" y="120" width="50" height="50" rx="4"/>
        <rect x="218" y="128" width="34" height="34" fill="#FFFFFF" rx="2"/>
        <rect x="226" y="136" width="18" height="18"/>
        <rect x="60" y="270" width="50" height="50" rx="4"/>
        <rect x="68" y="278" width="34" height="34" fill="#FFFFFF" rx="2"/>
        <rect x="76" y="286" width="18" height="18"/>
        <rect x="130" y="130" width="20" height="20"/>
        <rect x="160" y="150" width="20" height="20"/>
        <rect x="130" y="190" width="60" height="60" rx="4" fill="' . $color . '"/>
        <text x="160" y="226" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="bold" font-size="14" text-anchor="middle">KHQR</text>
        <rect x="210" y="270" width="20" height="20"/>
        <rect x="235" y="295" width="25" height="25"/>
      </g>
      <text x="160" y="370" fill="#64748B" font-family="system-ui, sans-serif" font-size="13" text-anchor="middle">KATTEB CODE ACADEMY</text>
    </svg>';
    file_put_contents($outFile, $qr);
}

makeQr("ABA BANK", "#004777", $qrDir . '/aba_qr.png');
makeQr("WING BANK", "#0088CE", $qrDir . '/wing_qr.png');
makeQr("ACLEDA BANK", "#0F4C81", $qrDir . '/acleda_qr.png');

// 4. Course Banners
function makeCourseBanner($title, $subtitle, $grad1, $grad2, $outFile) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
      <defs>
        <linearGradient id="bg_' . md5($title) . '" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" stop-color="' . $grad1 . '"/>
          <stop offset="100%" stop-color="' . $grad2 . '"/>
        </linearGradient>
      </defs>
      <rect width="800" height="450" fill="url(#bg_' . md5($title) . ')"/>
      <circle cx="700" cy="80" r="160" fill="rgba(255,255,255,0.05)"/>
      <circle cx="100" cy="380" r="120" fill="rgba(34,197,94,0.15)"/>
      <rect x="60" y="50" width="130" height="34" rx="6" fill="rgba(34,197,94,0.2)" stroke="#22C55E" stroke-width="1.5"/>
      <text x="125" y="73" fill="#22C55E" font-family="system-ui, sans-serif" font-weight="bold" font-size="13" text-anchor="middle">KATTEB CODE</text>
      <text x="60" y="190" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="bold" font-size="28">' . htmlspecialchars($title) . '</text>
      <text x="60" y="240" fill="#CBD5E1" font-family="system-ui, sans-serif" font-size="18">' . htmlspecialchars($subtitle) . '</text>
      <rect x="60" y="320" width="160" height="44" rx="8" fill="#22C55E"/>
      <text x="140" y="348" fill="#FFFFFF" font-family="system-ui, sans-serif" font-weight="bold" font-size="15" text-anchor="middle">ចូលរៀន &gt;</text>
    </svg>';
    file_put_contents($outFile, $svg);
}

makeCourseBanner("Modern Full-Stack Web Development", "PHP 8+, MySQL, JavaScript & Bootstrap 5", "#0F172A", "#1E293B", $thumbDir . '/course-fullstack.jpg');
makeCourseBanner("Mastering PHP 8 & Clean MVC", "Design Patterns & Framework Architecture", "#1E1E38", "#0F172A", $thumbDir . '/course-php-mvc.jpg');
makeCourseBanner("HTML5, CSS3 & Responsive UI", "មូលដ្ឋានគ្រឹះរចនាគេហទំព័រ (Free Course)", "#064E3B", "#0F172A", $thumbDir . '/course-html-css.jpg');
makeCourseBanner("MySQL Database Optimization", "Data Modeling, Indexing & High Performance", "#1E293B", "#334155", $thumbDir . '/course-mysql.jpg');

// Placeholders
copy($thumbDir . '/course-fullstack.jpg', $imgDir . '/placeholder-course.jpg');
copy($thumbDir . '/course-fullstack.jpg', $receiptDir . '/receipt-demo.jpg');

echo "Assets generated successfully.\n";
