<?php

class Router {
    private array $routes = [];

    // Question 1 : Comment ajouter une route GET ?
    public function get(string $path, callable|array $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    // Question 2 : Comment ajouter une route POST ?
    public function post(string $path, callable|array $callback): void {
        $this->routes['POST'][$path] = $callback;
    }

    // Question 3 : Comment exécuter la bonne route ?
    public function run(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (preg_match('#^/coverage/#', $uri)) {
            return;
        }

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];

            // Auto-instanciation si handler du type [Controller::class, 'method']
            if (is_array($handler) && count($handler) === 2 && is_string($handler[0])) {
                $controller = new $handler[0]();
                $methodName = $handler[1];
                $controller->$methodName();
            } else {
                call_user_func($handler);
            }
            return;
        }

        http_response_code(404);
        echo "404 - Page non trouvée";
    }
}
