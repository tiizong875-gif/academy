<?php
namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\Auth;
use App\Helpers\Session;

class AuthMiddleware {
    public function handle(Request $request): bool {
        if (!Auth::check()) {
            Session::flash('error', 'សូមចូលគណនី (Login) ជាមុនសិន ដើម្បីបន្តដំណើរការ');
            Response::redirect(url('/login'));
            return false;
        }
        return true;
    }
}
