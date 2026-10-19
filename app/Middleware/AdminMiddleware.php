<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\Auth;
use App\Helpers\Session;

class AdminMiddleware {
    public function handle(Request $request): bool {
        if (!Auth::check()) {
            Session::flash('error', 'សូមចូលគណនីជា Admin ដើម្បីចូលកាន់ផ្ទាំងគ្រប់គ្រង');
            Response::redirect(url('/login'));
            return false;
        }

        if (!Auth::isAdmin()) {
            Session::flash('error', 'លោកអ្នកគ្មានសិទ្ធិចូលកាន់ផ្ទាំងគ្រប់គ្រង Admin នេះទេ (Access Denied)');
            Response::redirect(url('/'));
            return false;
        }

        return true;
    }
}
