<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $action): void
    {
        $this->addRoutes('GET', $path, $action);
    }

    private function addRoutes(string $method, string $path, callable|array $action): void
    {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path);
        $this->routes[$method][] = [
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $action,
        ];

    }

    /**
     * @throws \Smarty\Exception
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {

                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler = $route['handler'];

                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $handler = [new $class(), $action];
                }

                echo call_user_func_array($handler, $params);
                return;
            }
        }

        http_response_code(404);
        echo TemplateRenderer::render('404.tpl', ['title' => 'Страница не найдена']);
    }
}