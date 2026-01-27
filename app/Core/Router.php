<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $path, $controller, $action)
    {
        // Convert path parameters {param} to regex
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        // Add start and end delimiters
        $path = '#^' . $path . '$#';

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($uri, $method)
    {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remove trailing slash except for root
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }

        // Support for subdirectory deployment if needed (adjust as necessary)
        // For now assuming root or relative to script name logic handled in index.php or here
        // If the app is in a subdirectory, we might need to strip that prefix.
        // Assuming running on root or handled via rewrite/apache alias.
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $uri, $matches)) {
                $controllerClass = "App\\Controllers\\" . $route['controller'];
                $action = $route['action'];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $action)) {
                        // Filter numeric keys from matches to get named params
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                        call_user_func_array([$controller, $action], $params);
                        return;
                    } else {
                        die("Method $action not found in controller $controllerClass");
                    }
                } else {
                    die("Controller class $controllerClass not found");
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }
}
