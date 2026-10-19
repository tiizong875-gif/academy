<?php
namespace App\Helpers;

use App\Models\User;

class Auth {
    private static ?array $cachedUser = null;

    public static function check(): bool {
        return Session::has('user_id');
    }

    public static function id(): ?int {
        return Session::get('user_id');
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
        Session::regenerate();
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        self::$cachedUser = $user;
    }

    public static function logout(): void {
        Session::destroy();
        self::$cachedUser = null;
    }
}
