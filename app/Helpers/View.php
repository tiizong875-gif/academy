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
        $cleanPath = ltrim($path, '/');

        // On Vercel or Root Domain deployments
        $isVercel = (getenv('VERCEL') || isset($_SERVER['VERCEL']) || str_ends_with($_SERVER['HTTP_HOST'] ?? '', '.vercel.app'));
        if ($isVercel) {
            return $cleanPath === '' ? '/' : '/' . $cleanPath;
        }

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
        $cleanPath = ltrim($path, '/');
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');

        // On Vercel or Production (detected via VERCEL env or root execution /api)
        if (getenv('VERCEL') || isset($_SERVER['VERCEL']) || str_starts_with($_SERVER['SCRIPT_NAME'] ?? '', '/api')) {
            return '/assets/' . $cleanPath;
        }

        // Local XAMPP with subfolder (e.g. /academy)
        if ($scriptDir !== '' && $scriptDir !== '.' && $scriptDir !== '/') {
            if (str_ends_with($scriptDir, '/public')) {
                return $scriptDir . '/assets/' . $cleanPath;
            }
            return $scriptDir . '/public/assets/' . $cleanPath;
        }

        return '/public/assets/' . $cleanPath;
    }
}

if (!function_exists('uploadUrl')) {
    function uploadUrl(?string $path, string $default = ''): string {
        if (!$path) {
            return $default ? asset($default) : asset('images/placeholder-course.jpg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');
        if (str_starts_with($cleanPath, 'uploads/')) {
            $cleanPath = substr($cleanPath, 8);
        }
        $cleanPath = preg_replace('#^(thumbnails|qr|receipts|profiles|videos)/\1/#', '$1/', $cleanPath);
        $cleanPath = 'uploads/' . $cleanPath;

        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');

        // On Vercel or Production
        if (getenv('VERCEL') || isset($_SERVER['VERCEL']) || str_starts_with($_SERVER['SCRIPT_NAME'] ?? '', '/api')) {
            return '/' . $cleanPath;
        }

        // Local XAMPP with subfolder
        if ($scriptDir !== '' && $scriptDir !== '.' && $scriptDir !== '/') {
            if (str_ends_with($scriptDir, '/public')) {
                return $scriptDir . '/' . $cleanPath;
            }
            return $scriptDir . '/' . $cleanPath;
        }

        return '/' . $cleanPath;
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
