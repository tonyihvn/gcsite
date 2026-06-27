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
        
        // Extract the path from REQUEST_URI - this is the raw request
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestPath = parse_url($requestUri, PHP_URL_PATH);
        
        // Get the script name (typically /index.php or empty)
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $scriptDir = dirname($scriptName);
        
        // DEBUG logging - helps diagnose routing issues
        $debug = ($_ENV['APP_DEBUG'] === 'true');
        if ($debug) {
            error_log("=== ROUTER DEBUG START ===");
            error_log("REQUEST_URI: " . $requestUri);
            error_log("REQUEST_PATH: " . $requestPath);
            error_log("SCRIPT_NAME: " . $scriptName);
            error_log("SCRIPT_DIR: " . $scriptDir);
            error_log("REQUEST_METHOD: " . $requestMethod);
        }
        
        // Remove script directory from path if present
        if ($scriptDir !== '/' && !empty($scriptDir)) {
            if (strpos($requestPath, $scriptDir) === 0) {
                $requestPath = substr($requestPath, strlen($scriptDir));
                if ($debug) error_log("After removing scriptDir: " . $requestPath);
            }
        }
        
        // Remove index.php from path if present (shouldn't be, but just in case)
        $requestPath = str_replace('/index.php', '', $requestPath);
        
        // Trim slashes
        $requestPath = trim($requestPath, '/');
        
        // Handle empty path - front page
        if (empty($requestPath)) {
            $requestPath = '';
        }
        
        if ($debug) {
            error_log("Final extracted path: '" . $requestPath . "'");
            error_log("Looking for route: [$requestMethod] '$requestPath'");
        }

        // Check exact route match
        if (isset($this->routes[$requestMethod][$requestPath])) {
            $route = $this->routes[$requestMethod][$requestPath];
            $this->currentRoute = $route;
            if ($debug) error_log("FOUND exact match: " . $route['controller'] . "@" . $route['method']);
            if ($debug) error_log("=== ROUTER DEBUG END ===");
            return $this->call($route['controller'], $route['method']);
        }

        // Try to match dynamic routes
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $path => $route) {
                if ($this->matchRoute($path, $requestPath, $matches)) {
                    $route['params'] = $matches;
                    $this->currentRoute = $route;
                    if ($debug) error_log("FOUND dynamic match: " . $path . " => " . $route['controller'] . "@" . $route['method']);
                    if ($debug) error_log("Params: " . json_encode($matches));
                    if ($debug) error_log("=== ROUTER DEBUG END ===");
                    return $this->call($route['controller'], $route['method'], $matches);
                }
            }
        }

        // No route found - 404
        if ($debug) {
            error_log("NO ROUTE FOUND!");
            error_log("Available routes for $requestMethod:");
            if (isset($this->routes[$requestMethod])) {
                foreach (array_keys($this->routes[$requestMethod]) as $route) {
                    error_log("  - $route");
                }
            } else {
                error_log("  - (no routes registered for this method)");
            }
            error_log("=== ROUTER DEBUG END ===");
        }
        
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
