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
        if (!$token || !$sessionToken) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }

    public static function field(): string {
        $token = self::token();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
