<?php
/**
 * DotEnv - Environment Variable Loader
 * GINTEC Solutions
 */

namespace Core;

class DotEnv
{
    private $path;
    private $variables = [];

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function load()
    {
        if (!is_file($this->path)) {
            throw new \Exception("Environment file not found: {$this->path}");
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (strpos($line, '#') === 0) {
                continue;
            }

            // Parse variable
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes
                if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                    (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                    $value = substr($value, 1, -1);
                }

                putenv("$key=$value");
                $_ENV[$key] = $value;
                $this->variables[$key] = $value;
            }
        }

        return $this->variables;
    }
}
