<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Env;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : dirname(__DIR__, 2);

        return $path === '' ? $basePath : $basePath . DIRECTORY_SEPARATOR . ltrim($path, '\\/');
    }
}

if (!function_exists('app_path')) {
    function app_path(string $path = ''): string
    {
        return base_path('app' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('config_path')) {
    function config_path(string $path = ''): string
    {
        return app_path('config' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return base_path('public' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return base_path('storage' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('database_path')) {
    function database_path(string $path = ''): string
    {
        return base_path('database' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('docs_path')) {
    function docs_path(string $path = ''): string
    {
        return base_path('docs' . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path));
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('render_view')) {
    function render_view(string $viewPath, array $data = []): string
    {
        $filePath = base_path($viewPath);
        if (!is_file($filePath)) {
            throw new RuntimeException('View tidak ditemukan: ' . $viewPath);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $filePath;

        return (string) ob_get_clean();
    }
}

if (!function_exists('boolean_env')) {
    function boolean_env(string $key, bool $default = false): bool
    {
        $value = env($key, $default ? '1' : '0');

        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }
}

if (!function_exists('render_layout')) {
    function render_layout(string $title, string $content): string
    {
        $appName = (string) config('app.name', 'DAYA Platform');
        $csrfToken = '';
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (!isset($_SESSION['_csrf_token'])) {
                $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
            }
            $csrfToken = (string) $_SESSION['_csrf_token'];
        }
        $cssUrl = asset_url('css/app.css');
        $jsUrl = asset_url('js/app.js');

        return <<<HTML
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title} | {$appName}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{$cssUrl}" rel="stylesheet">
</head>
<body data-csrf-token="{$csrfToken}">
    <header class="border-bottom bg-white sticky-top">
        <div class="container py-3 d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div>
                <div class="text-uppercase small text-muted fw-semibold">DAYA Platform</div>
                <div class="fw-bold fs-5">Project Bootstrap</div>
            </div>
            <nav class="d-flex gap-2 flex-wrap">
                <a class="btn btn-sm btn-outline-dark" href="/">Home</a>
                <a class="btn btn-sm btn-outline-dark" href="/health">Health</a>
                <a class="btn btn-sm btn-outline-dark" href="/install">Installer</a>
                <a class="btn btn-sm btn-outline-dark" href="/bootstrap/protected">Protected</a>
            </nav>
        </div>
    </header>
    <main class="container py-4 py-md-5">
        {$content}
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{$jsUrl}"></script>
</body>
</html>
HTML;
    }
}
