<?php

declare(strict_types=1);

namespace App\Core;

final class Autoloader
{
    private array $prefixes = [];

    public function addNamespace(string $prefix, string $baseDirectory): void
    {
        $this->prefixes[rtrim($prefix, '\\') . '\\'] = rtrim($baseDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'autoload']);
    }

    private function autoload(string $className): void
    {
        foreach ($this->prefixes as $prefix => $baseDirectory) {
            if (!str_starts_with($className, $prefix)) {
                continue;
            }

            $relativeClass = substr($className, strlen($prefix));
            $file = $baseDirectory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (is_file($file)) {
                require_once $file;

                return;
            }

            $segments = explode('\\', $relativeClass);
            $classFile = array_pop($segments);
            $lowercasePath = $baseDirectory;

            foreach ($segments as $segment) {
                $lowercasePath .= strtolower($segment) . DIRECTORY_SEPARATOR;
            }

            $fallbackFile = $lowercasePath . $classFile . '.php';
            if (is_file($fallbackFile)) {
                require_once $fallbackFile;

                return;
            }
        }
    }
}
