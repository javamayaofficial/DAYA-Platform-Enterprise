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
        return app_url('/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('app_base_url_path')) {
    function app_base_url_path(): string
    {
        $configuredUrl = trim((string) config('app.url', ''));
        $configuredPath = $configuredUrl !== '' ? (string) parse_url($configuredUrl, PHP_URL_PATH) : '';
        $normalizedConfiguredPath = trim($configuredPath, '/');
        if ($normalizedConfiguredPath !== '') {
            return '/' . $normalizedConfiguredPath;
        }

        $scriptName = (string) ($_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDirectory = trim(str_replace('\\', '/', dirname($scriptName)), '/.');
        if ($scriptDirectory === '') {
            return '';
        }

        return '/' . $scriptDirectory;
    }
}

if (!function_exists('app_url')) {
    function app_url(string $path = '/'): string
    {
        if ($path === '') {
            $path = '/';
        }

        if (preg_match('#^[a-z][a-z0-9+\-.]*://#i', $path) === 1 || str_starts_with($path, '//')) {
            return $path;
        }

        $basePath = app_base_url_path();
        if ($path === '/') {
            return $basePath !== '' ? $basePath : '/';
        }

        $normalizedPath = str_starts_with($path, '/') ? $path : '/' . ltrim($path, '/');
        if ($basePath === '' || str_starts_with($normalizedPath, $basePath . '/') || $normalizedPath === $basePath) {
            return $normalizedPath;
        }

        return $basePath . $normalizedPath;
    }
}

if (!function_exists('normalize_app_request_path')) {
    function normalize_app_request_path(string $path): string
    {
        $basePath = app_base_url_path();
        if ($basePath === '') {
            return $path === '' ? '/' : $path;
        }

        if ($path === $basePath) {
            return '/';
        }

        if (str_starts_with($path, $basePath . '/')) {
            $trimmedPath = substr($path, strlen($basePath));
            return $trimmedPath === '' ? '/' : $trimmedPath;
        }

        return $path === '' ? '/' : $path;
    }
}

if (!function_exists('rewrite_root_relative_urls')) {
    function rewrite_root_relative_urls(string $html): string
    {
        $basePath = app_base_url_path();
        if ($basePath === '') {
            return $html;
        }

        return (string) preg_replace_callback(
            '/\b(href|src|action|formaction)=([\"\'])(\/(?!\/)[^\"\']*)\2/i',
            static function (array $matches) use ($basePath): string {
                $attribute = $matches[1];
                $quote = $matches[2];
                $url = $matches[3];

                if ($url === $basePath || str_starts_with($url, $basePath . '/')) {
                    return $attribute . '=' . $quote . $url . $quote;
                }

                return $attribute . '=' . $quote . $basePath . $url . $quote;
            },
            $html
        );
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

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return '';
        }

        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_input')) {
    function csrf_input(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('render_layout')) {
    function render_layout(string $title, string $content): string
    {
        $appName = (string) config('app.name', 'DAYA Platform');
        $csrfToken = csrf_token();
        $cssUrl = asset_url('css/app.css');
        $jsUrl = asset_url('js/app.js');

        $isDevelopment = (string) config('app.env', 'production') === 'development';
        $exposeDiagnostics = $isDevelopment || (bool) config('app.debug', false);
        $showInstallerLink = !(bool) config('app.installed', false) || $exposeDiagnostics;
        $healthLink = $exposeDiagnostics ? '<a class="btn btn-sm btn-outline-dark" href="' . e(app_url('/health')) . '">Health</a>' : '';
        $installerLink = $showInstallerLink ? '<a class="btn btn-sm btn-outline-dark" href="' . e(app_url('/install')) . '">Installer</a>' : '';
        $protectedLink = $isDevelopment ? '<a class="btn btn-sm btn-outline-dark" href="' . e(app_url('/bootstrap/protected')) . '">Protected</a>' : '';
        $content = rewrite_root_relative_urls($content);
        $homeUrl = e(app_url('/'));

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
                <a class="btn btn-sm btn-outline-dark" href="{$homeUrl}">Home</a>
                {$healthLink}
                {$installerLink}
                {$protectedLink}
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
