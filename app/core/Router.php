<?php
class Router {
    private array $routes = [];

    public function get(string $path, callable|array $handler) {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }
    public function post(string $path, callable|array $handler) {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string {
        $path = '/' . trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    public function dispatch(string $baseUrl) {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        if ($scriptDir && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $uri = $this->normalize($uri);
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $handler = $this->routes[$method][$uri] ?? null;
        if (!$handler) {
            // support simple /posts/show?id=...
            $pathOnly = preg_replace('#\?.*$#','',$uri);
            $handler = $this->routes[$method][$pathOnly] ?? null;
        }

        if (!$handler) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_array($handler)) {
            [$class, $action] = $handler;
            $controller = new $class();
            return call_user_func([$controller, $action]);
        }
        return call_user_func($handler);
    }
}
