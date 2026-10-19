<?php
namespace App\Core;

class Router {
    private array $routes = [];
    private array $groupMiddleware = [];
    private string $groupPrefix = '';

    public function group(array $attributes, callable $callback): void {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        if (isset($attributes['prefix'])) {
            $this->groupPrefix = rtrim($this->groupPrefix . '/' . trim($attributes['prefix'], '/'), '/');
        }

        if (isset($attributes['middleware'])) {
            $middlewares = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            $this->groupMiddleware = array_merge($this->groupMiddleware, $middlewares);
        }

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    public function get(string $path, array|callable $action, array|string $middleware = []): self {
        return $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, array|callable $action, array|string $middleware = []): self {
        return $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put(string $path, array|callable $action, array|string $middleware = []): self {
        return $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete(string $path, array|callable $action, array|string $middleware = []): self {
        return $this->addRoute('DELETE', $path, $action, $middleware);
    }

    private function addRoute(string $method, string $path, array|callable $action, array|string $middleware = []): self {
        $fullPath = $this->groupPrefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');
        if ($fullPath === '//') $fullPath = '/';

        $middlewares = is_array($middleware) ? $middleware : ($middleware ? [$middleware] : []);
        $allMiddlewares = array_merge($this->groupMiddleware, $middlewares);

        $this->routes[] = [
            'method'     => $method,
            'path'       => $fullPath,
            'action'     => $action,
            'middleware' => $allMiddlewares
        ];

        return $this;
    }

    public function dispatch(Request $request): void {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method && !($method === 'HEAD' && $route['method'] === 'GET')) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                // Filter string keys for route parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Execute middlewares
                foreach ($route['middleware'] as $mw) {
                    $mwInstance = is_string($mw) ? new $mw() : $mw;
                    $result = $mwInstance->handle($request);
                    if ($result === false) {
                        return; // Stopped by middleware
                    }
                }

                // Call Action
                $action = $route['action'];
                if (is_callable($action)) {
                    call_user_func_array($action, array_values($params));
                    return;
                }

                if (is_array($action)) {
                    [$controllerClass, $methodName] = $action;
                    $controller = new $controllerClass();
                    call_user_func_array([$controller, $methodName], array_values($params));
                    return;
                }
            }
        }

        // 404 handler
        Response::notFound();
    }
}
