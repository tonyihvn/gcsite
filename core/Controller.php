<?php
/**
 * Base Controller Class
 * GINTEC Solutions
 */

namespace Core;

class Controller
{
    protected $config;
    protected $db;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/app.php';
        $dbConfig = require __DIR__ . '/../config/database.php';
        $this->db = Database::getInstance($dbConfig)->getConnection();
    }

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../app/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            die("View not found: $viewPath");
        }

        // Check if this is an admin view
        if (strpos($view, 'admin.') === 0) {
            // Use admin layout
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            include __DIR__ . '/../app/views/layouts/admin.php';
        } else {
            // Use regular layout
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            include __DIR__ . '/../app/views/layouts/app.php';
        }
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($path)
    {
        $baseUrl = rtrim($this->config['url'], '/');
        header("Location: $baseUrl/$path");
        exit;
    }

    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $r) {
                list($ruleName, $ruleValue) = array_pad(explode(':', $r, 2), 2, null);

                if (!isset($data[$field]) || empty($data[$field])) {
                    if ($ruleName === 'required') {
                        $errors[$field] = ucfirst($field) . ' is required';
                    }
                } else {
                    $this->checkRule($field, $data[$field], $ruleName, $ruleValue, $errors);
                }
            }
        }

        return $errors;
    }

    private function checkRule($field, $value, $rule, $ruleValue, &$errors)
    {
        switch ($rule) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Invalid email format';
                }
                break;
            case 'min':
                if (strlen($value) < (int)$ruleValue) {
                    $errors[$field] = "Minimum length is $ruleValue characters";
                }
                break;
            case 'max':
                if (strlen($value) > (int)$ruleValue) {
                    $errors[$field] = "Maximum length is $ruleValue characters";
                }
                break;
            case 'numeric':
                if (!is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' must be numeric';
                }
                break;
            case 'unique':
                // Will be implemented in model
                break;
        }
    }

    protected function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? null;
    }

    protected function success($message, $data = null)
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    protected function error($message, $data = null, $statusCode = 400)
    {
        http_response_code($statusCode);
        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
        ];
    }
}
