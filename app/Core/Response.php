<?php
namespace App\Core;

class Response {
    public static function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function redirect(string $url, int $status = 302): void {
        header("Location: {$url}", true, $status);
        exit;
    }

    public static function notFound(string $message = 'Page Not Found'): void {
        http_response_code(404);
        require __DIR__ . '/../Views/pages/404.php';
        exit;
    }
}
