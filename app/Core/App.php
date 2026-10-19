<?php
namespace App\Core;

class App {
    private static Request $request;
    private static Router $router;

    public static function run(): void {
        // Start secure session if not started
        if (session_status() === PHP_SESSION_NONE) {
            // If running on Vercel/Serverless (read-only filesystem except /tmp)
            if (getenv('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
                if (is_dir('/tmp') && is_writable('/tmp')) {
                    session_save_path('/tmp');
                }
            }

            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');

            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                       || ($_SERVER['SERVER_PORT'] ?? 80) == 443
                       || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

            session_set_cookie_params([
                'lifetime' => 86400 * 7,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }

        // Set timezone
        date_default_timezone_set('Asia/Phnom_Penh');

        // Initialize Request & Router
        self::$request = new Request();
        self::$router = new Router();

        // Load Routes
        $router = self::$router;
        require __DIR__ . '/../../routes/web.php';

        // Dispatch Request
        self::$router->dispatch(self::$request);
    }

    public static function request(): Request {
        if (!isset(self::$request)) {
            self::$request = new Request();
        }
        return self::$request;
    }

    public static function router(): Router {
        if (!isset(self::$router)) {
            self::$router = new Router();
        }
        return self::$router;
    }
}
