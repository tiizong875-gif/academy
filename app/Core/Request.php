<?php
namespace App\Core;

class Request {
    private array $get;
    private array $post;
    private array $files;
    private array $server;
    private ?array $json = null;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;

        $contentType = $this->server['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $raw = file_get_contents('php://input');
            $this->json = json_decode($raw, true) ?? [];
        }
    }

    public function method(): string {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST' && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }
        return $method;
    }

    public function uri(): string {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        
        $scriptName = str_replace('\\', '/', $this->server['SCRIPT_NAME'] ?? '');
        $dir = str_replace('\\', '/', dirname($scriptName));
        
        // If script is in /public, the parent folder is the base
        if (str_ends_with($dir, '/public')) {
            $baseDir = str_replace('\\', '/', dirname($dir));
        } else {
            $baseDir = $dir;
        }
        $baseDir = rtrim($baseDir, '/');

        // Strip base folder if present
        if ($baseDir !== '' && str_starts_with($path, $baseDir)) {
            $path = substr($path, strlen($baseDir));
        }

        // Strip /public if present
        if (str_starts_with($path, '/public')) {
            $path = substr($path, 7);
        }

        $path = '/' . trim($path, '/');
        return ($path === '' || $path === '//') ? '/' : $path;
    }

    public function input(string $key, mixed $default = null): mixed {
        if ($this->json !== null && array_key_exists($key, $this->json)) {
            return $this->json[$key];
        }
        if (array_key_exists($key, $this->post)) {
            return $this->post[$key];
        }
        if (array_key_exists($key, $this->get)) {
            return $this->get[$key];
        }
        return $default;
    }

    public function all(): array {
        if ($this->json !== null) {
            return array_merge($this->get, $this->json);
        }
        return array_merge($this->get, $this->post);
    }

    public function file(string $key): ?array {
        return $this->files[$key] ?? null;
    }

    public function isAjax(): bool {
        return (!empty($this->server['HTTP_X_REQUESTED_WITH']) &&
                strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
               str_contains($this->server['HTTP_ACCEPT'] ?? '', 'application/json');
    }
}
