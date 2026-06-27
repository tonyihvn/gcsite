<?php
/**
 * Application Router
 * GINTEC Solutions
 */

namespace Core;

class Router
{
    private $routes = [];
    private $currentRoute = null;

    public function get($path, $controller, $method = 'index')
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    public function post($path, $controller, $method = 'store')
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    public function put($path, $controller, $method = 'update')
    {
        $this->addRoute('PUT', $path, $controller, $method);
    }

    public function delete($path, $controller, $method = 'destroy')
    {
        $this->addRoute('DELETE', $path, $controller, $method);
    }

    private function addRoute($httpMethod, $path, $controller, $method)
    {
        $path = trim($path, '/');
        $this->routes[$httpMethod][$path] = [
            'controller' => $controller,
            'method' => $method,
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestPath = str_replace(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')), '', $requestPath);
        $requestPath = trim($requestPath, '/');

        // Handle empty path
        if (empty($requestPath)) {
            $requestPath = '';
        }

        // Check exact route match
        if (isset($this->routes[$requestMethod][$requestPath])) {
            $route = $this->routes[$requestMethod][$requestPath];
            $this->currentRoute = $route;
            return $this->call($route['controller'], $route['method']);
        }

        // Try to match dynamic routes
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $path => $route) {
                if ($this->matchRoute($path, $requestPath, $matches)) {
                    $route['params'] = $matches;
                    $this->currentRoute = $route;
                    return $this->call($route['controller'], $route['method'], $matches);
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        $errorFile = __DIR__ . '/../app/views/errors/404.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "404 - Page not found";
        }
        exit;
    }

    private function matchRoute($pattern, $path, &$matches = [])
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([a-zA-Z0-9_-]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches);
            return true;
        }

        return false;
    }

    private function call($controller, $method, $params = [])
    {
        $controllerClass = 'App\\Controllers\\' . ucfirst($controller) . 'Controller';

        if (!class_exists($controllerClass)) {
            $appRoot = defined('APP_ROOT') ? APP_ROOT : 'UNKNOWN';
            $expectedPath = $appRoot . '/app/Controllers/' . ucfirst($controller) . 'Controller.php';
            error_log("Controller class not found: $controllerClass. Expected file: $expectedPath");
            http_response_code(500);
            die("Controller not found: $controllerClass\n" .
                "Expected file: $expectedPath\n" .
                "APP_ROOT: $appRoot\n" .
                "Check error logs for more details.");
        }

        $instance = new $controllerClass();
        
        if (!method_exists($instance, $method)) {
            http_response_code(500);
            die("Method not found: $method in $controllerClass");
        }

        call_user_func_array([$instance, $method], $params);
    }
}
