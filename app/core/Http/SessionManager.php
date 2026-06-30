<?php

declare(strict_types=1);

namespace App\Core\Http;

final class SessionManager
{
    public function __construct(private readonly array $config = [])
    {
    }

    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $this->configureSavePath();
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        session_name((string) ($this->config['name'] ?? 'daya_session'));
        session_set_cookie_params([
            'lifetime' => ((int) ($this->config['lifetime'] ?? 120)) * 60,
            'path' => '/',
            'secure' => (bool) ($this->config['secure'] ?? false),
            'httponly' => (bool) ($this->config['http_only'] ?? true),
            'samesite' => (string) ($this->config['same_site'] ?? 'Lax'),
        ]);

        session_start();
    }

    private function configureSavePath(): void
    {
        $configuredPath = trim((string) ($this->config['save_path'] ?? ''));
        if ($configuredPath !== '' && is_dir($configuredPath) && is_writable($configuredPath)) {
            ini_set('session.save_path', $configuredPath);
            session_save_path($configuredPath);

            return;
        }

        $runtimeSavePath = trim((string) (ini_get('session.save_path') ?: ''));
        if ($runtimeSavePath !== '' && is_dir($runtimeSavePath) && is_writable($runtimeSavePath)) {
            ini_set('session.save_path', $runtimeSavePath);
            session_save_path($runtimeSavePath);

            return;
        }

        $fallbackPath = \storage_path('sessions');
        if (!is_dir($fallbackPath) && !mkdir($fallbackPath, 0775, true) && !is_dir($fallbackPath)) {
            throw new \RuntimeException('Direktori session tidak dapat dibuat.');
        }

        ini_set('session.save_path', $fallbackPath);
        session_save_path($fallbackPath);
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public function pullFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public function csrfToken(): string
    {
        return \csrf_token();
    }

    public function invalidate(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 3600,
                'path' => $params['path'] ?: '/',
                'domain' => $params['domain'] ?: '',
                'secure' => (bool) ($params['secure'] ?? false),
                'httponly' => (bool) ($params['httponly'] ?? true),
                'samesite' => (string) ($params['samesite'] ?? 'Lax'),
            ]);
            session_destroy();
        }
    }
}
