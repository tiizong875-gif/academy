<?php
namespace App\Helpers;

use App\Models\User;

class Auth {
    private static ?array $cachedUser = null;

    public static function check(): bool {
        return Session::has('user_id') || self::checkAuthCookie();
    }

    public static function id(): ?int {
        return Session::get('user_id') ?: self::getAuthCookieId();
    }

    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }

        if (self::$cachedUser === null) {
            $userModel = new User();
            self::$cachedUser = $userModel->findById(self::id());
        }

        return self::$cachedUser;
    }

    public static function role(): ?string {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool {
        return self::role() === 'admin';
    }

    public static function isInstructor(): bool {
        return in_array(self::role(), ['instructor', 'admin']);
    }

    public static function isStudent(): bool {
        return self::role() === 'student';
    }

    public static function login(array $user): void {
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        self::$cachedUser = $user;

        // Set signed authentication cookie for serverless persistence
        $cookieData = $user['id'] . '|' . $user['role'] . '|' . md5($user['id'] . $user['password'] . 'katteb_secret');
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                   || ($_SERVER['SERVER_PORT'] ?? 80) == 443
                   || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

        setcookie('katteb_auth', $cookieData, [
            'expires'  => time() + (86400 * 30),
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    public static function logout(): void {
        Session::destroy();
        self::$cachedUser = null;
        setcookie('katteb_auth', '', time() - 3600, '/');
    }

    private static function checkAuthCookie(): bool {
        return self::getAuthCookieId() !== null;
    }

    private static function getAuthCookieId(): ?int {
        if (empty($_COOKIE['katteb_auth'])) {
            return null;
        }

        $parts = explode('|', $_COOKIE['katteb_auth']);
        if (count($parts) !== 3) {
            return null;
        }

        $id = (int)$parts[0];
        $role = $parts[1];
        $hash = $parts[2];

        $userModel = new User();
        $user = $userModel->findById($id);
        if (!$user) {
            return null;
        }

        $expectedHash = md5($user['id'] . $user['password'] . 'katteb_secret');
        if (!hash_equals($expectedHash, $hash)) {
            return null;
        }

        // Restore to session
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        self::$cachedUser = $user;

        return $id;
    }
}
