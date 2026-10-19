<?php
namespace App\Helpers;

class Upload {
    private static array $allowedImages = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private static array $allowedVideos = ['video/mp4', 'video/webm', 'video/ogg'];
    private static int $maxImageSize = 5 * 1024 * 1024; // 5 MB
    private static int $maxVideoSize = 100 * 1024 * 1024; // 100 MB

    public static function image(array $file, string $targetFolder = 'thumbnails'): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'មានបញ្ហាក្នុងការ Upload រូបភាព (Error code: ' . $file['error'] . ')'];
        }

        if ($file['size'] > self::$maxImageSize) {
            return ['success' => false, 'error' => 'ទំហំរូបភាពមិនត្រូវលើសពី 5MB ឡើយ'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::$allowedImages)) {
            return ['success' => false, 'error' => 'ប្រភេទរូបភាពមិនត្រឹមត្រូវ (អនុញ្ញាតតែ JPG, PNG, WEBP, GIF)'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . strtolower($extension);

        // Upload directory
        $destinationDir = __DIR__ . '/../../public/uploads/' . trim($targetFolder, '/');
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $targetPath = $destinationDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'មិនអាចរក្សាទុករូបភាពនៅលើ Server បានទេ'];
        }

        return [
            'success'  => true,
            'filename' => $filename,
            'path'     => 'uploads/' . trim($targetFolder, '/') . '/' . $filename
        ];
    }

    public static function receipt(array $file): array {
        return self::image($file, 'receipts');
    }

    public static function profile(array $file): array {
        return self::image($file, 'profiles');
    }

    public static function thumbnail(array $file): array {
        return self::image($file, 'thumbnails');
    }

    public static function qr(array $file): array {
        return self::image($file, 'qr');
    }

    public static function video(array $file): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'មានបញ្ហាក្នុងការ Upload វីដេអូ (Error code: ' . $file['error'] . ')'];
        }

        if ($file['size'] > self::$maxVideoSize) {
            return ['success' => false, 'error' => 'ទំហំវីដេអូមិនត្រូវលើសពី 100MB ឡើយ'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, self::$allowedVideos)) {
            return ['success' => false, 'error' => 'ប្រភេទវីដេអូមិនត្រឹមត្រូវ (អនុញ្ញាតតែ MP4, WebM, OGG)'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['mp4', 'webm', 'ogg'])) {
            return ['success' => false, 'error' => 'កន្ទុយឯកសារវីដេអូមិនត្រឹមត្រូវ'];
        }

        $filename = uniqid('vid_', true) . '.' . $extension;

        $destinationDir = __DIR__ . '/../../public/uploads/videos';
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        $targetPath = $destinationDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'មិនអាចរក្សាទុកវីដេអូនោះនៅលើ Server បានទេ'];
        }

        return [
            'success'  => true,
            'filename' => $filename,
            'path'     => 'uploads/videos/' . $filename
        ];
    }
}
