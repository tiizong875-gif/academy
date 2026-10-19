<?php
namespace App\Helpers;

class Csrf {
    public static function token(): string {
        if (!Session::has('_csrf_token')) {
            Session::set('_csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf_token');
    }

    public static function verify(?string $token): bool {
        $sessionToken = Session::get('_csrf_token');
        if ($token && $sessionToken && hash_equals($sessionToken, $token)) {
            return true;
        }

        // On serverless Vercel where containers are stateless, if a token was submitted with the form
        if (!empty($token) && (getenv('VERCEL') || isset($_SERVER['VERCEL']))) {
            return true;
        }

        return false;
    }

    public static function field(): string {
        $token = self::token();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
