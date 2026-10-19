<?php
namespace App\Controllers;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Helpers\Csrf;
use App\Helpers\Session;

abstract class Controller {
    protected Request $request;

    public function __construct() {
        $this->request = App::request();
    }

    protected function render(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void {
        view($viewPath, $data, $layout);
    }

    protected function json(mixed $data, int $status = 200): void {
        Response::json($data, $status);
    }

    protected function redirect(string $path): void {
        Response::redirect(url($path));
    }

    protected function redirectBack(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        Response::redirect($referer);
    }

    protected function withSuccess(string $message): self {
        Session::flash('success', $message);
        return $this;
    }

    protected function withError(string $message): self {
        Session::flash('error', $message);
        return $this;
    }

    protected function withWarning(string $message): self {
        Session::flash('warning', $message);
        return $this;
    }

    protected function withOld(array $data): self {
        foreach ($data as $key => $val) {
            if ($key !== 'password' && $key !== 'password_confirmation') {
                Session::flash('old_' . $key, $val);
            }
        }
        return $this;
    }

    protected function validateCsrf(): bool {
        $token = $this->request->input('_csrf_token');
        if (!Csrf::verify($token)) {
            $this->withError('សំណើមិនត្រឹមត្រូវ ឬផុតកំណត់ សូមព្យាយាមម្ដងទៀត (Invalid CSRF Token)');
            $this->redirectBack();
            return false;
        }
        return true;
    }
}
