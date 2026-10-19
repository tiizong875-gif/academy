<?php
use App\Helpers\Csrf;
use App\Helpers\Session;
use App\Helpers\Auth;

if (!function_exists('view')) {
    function view(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void {
        extract($data);
        
        $viewFile = __DIR__ . '/../Views/' . str_replace('.', '/', $viewPath) . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: " . htmlspecialchars($viewFile));
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout !== null) {
            $layoutFile = __DIR__ . '/../Views/' . str_replace('.', '/', $layout) . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        echo $content;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');

        // Check if user is browsing through /public explicitly or through root rewrite
        if (!str_contains($reqUri, '/public') && str_ends_with($scriptDir, '/public')) {
            $scriptDir = dirname($scriptDir);
            $scriptDir = str_replace('\\', '/', $scriptDir);
            $scriptDir = rtrim($scriptDir, '/');
        }
        
        $cleanPath = ltrim($path, '/');
        if ($cleanPath === '') {
            return ($scriptDir === '' || $scriptDir === '.') ? '/' : $scriptDir;
        }

        return (($scriptDir === '' || $scriptDir === '.') ? '' : $scriptDir) . '/' . $cleanPath;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');
        $cleanPath = ltrim($path, '/');

        // If running where document root is already public/ (e.g. Vercel, production hosting)
        if (file_exists(__DIR__ . '/../../public/assets/' . $cleanPath) && basename($_SERVER['DOCUMENT_ROOT'] ?? '') === 'public') {
            return '/assets/' . $cleanPath;
        }

        // If user is accessing directly via /public
        if (str_contains($reqUri, '/public') || str_ends_with($scriptDir, '/public')) {
            $publicDir = str_ends_with($scriptDir, '/public') ? $scriptDir : $scriptDir . '/public';
            return $publicDir . '/assets/' . $cleanPath;
        }

        return ($scriptDir === '' || $scriptDir === '.' ? '' : $scriptDir) . '/public/assets/' . $cleanPath;
    }
}

if (!function_exists('uploadUrl')) {
    function uploadUrl(?string $path, string $default = ''): string {
        if (!$path) {
            return $default ? asset($default) : asset('images/placeholder-course.jpg');
        }

        $cleanPath = ltrim($path, '/');

        // Automatically prepend 'uploads/' if not already included
        if (!str_starts_with($cleanPath, 'uploads/')) {
            $cleanPath = 'uploads/' . $cleanPath;
        }

        $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');

        // If document root is public/
        if (basename($_SERVER['DOCUMENT_ROOT'] ?? '') === 'public') {
            return '/' . $cleanPath;
        }

        // If user is accessing directly via /public
        if (str_contains($reqUri, '/public') || str_ends_with($scriptDir, '/public')) {
            $publicDir = str_ends_with($scriptDir, '/public') ? $scriptDir : $scriptDir . '/public';
            return $publicDir . '/' . $cleanPath;
        }

        // Standard clean rewrite URL
        return ($scriptDir === '' || $scriptDir === '.' ? '' : $scriptDir) . '/' . $cleanPath;
    }
}

if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        return Csrf::token();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return Csrf::field();
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed {
        $old = Session::getFlash('old_' . $key);
        return $old !== null ? e($old) : e((string)$default);
    }
}
