<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Http\Response;
use RuntimeException;

final class Installer
{
    public function handle(Request $request): Response
    {
        if (!$this->canAccessInstaller()) {
            $content = '<div class="card shadow-sm border-0"><div class="card-body p-4">'
                . '<span class="badge text-bg-danger mb-3">403</span>'
                . '<h1 class="h4 mb-3">Installer dikunci.</h1>'
                . '<p class="text-secondary">Aplikasi sudah terpasang. Installer hanya dapat diakses saat development atau debug aktif.</p>'
                . '<a class="btn btn-dark" href="/">Kembali</a>'
                . '</div></div>';

            return Response::html(render_layout('Installer Dikunci', $content), 403);
        }

        if ($request->method() === 'POST') {
            return $this->install($request);
        }

        return Response::html(render_layout('Installer', $this->renderForm($request)));
    }

    private function install(Request $request): Response
    {
        $session = $request->session();
        $token = (string) $request->input('_csrf_token', '');

        if (!hash_equals($session->csrfToken(), $token)) {
            return Response::html(
                render_layout('Installer', $this->renderForm($request, ['Token CSRF tidak valid.'])),
                422
            );
        }

        $appName = trim((string) $request->input('app_name', 'DAYA Platform'));
        $appUrl = trim((string) $request->input('app_url', 'http://localhost'));
        $appEnv = trim((string) $request->input('app_env', 'production'));
        $appDebug = $request->input('app_debug', '0') === '1' ? '1' : '0';
        $timezone = trim((string) $request->input('app_timezone', 'Asia/Jakarta'));

        $dbHost = trim((string) $request->input('db_host', '127.0.0.1'));
        $dbPort = (int) $request->input('db_port', 3306);
        $dbName = trim((string) $request->input('db_database', ''));
        $dbUser = trim((string) $request->input('db_username', ''));
        $dbPass = (string) $request->input('db_password', '');

        $errors = [];
        if ($appName === '') {
            $errors[] = 'Nama aplikasi wajib diisi.';
        }
        if ($appUrl === '') {
            $errors[] = 'URL aplikasi wajib diisi.';
        }
        if ($dbName === '' || $dbUser === '') {
            $errors[] = 'Database name dan username wajib diisi.';
        }

        if ($errors !== []) {
            return Response::html(render_layout('Installer', $this->renderForm($request, $errors)), 422);
        }

        try {
            $sessionSecure = strtolower((string) parse_url($appUrl, PHP_URL_SCHEME)) === 'https';

            Database::test([
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset' => 'utf8mb4',
            ]);

            $this->writeEnvironment([
                'APP_NAME' => $appName,
                'APP_ENV' => $appEnv,
                'APP_DEBUG' => $appDebug,
                'APP_URL' => $appUrl,
                'APP_TIMEZONE' => $timezone,
                'APP_INSTALLED' => '1',
                'APP_KEY' => bin2hex(random_bytes(16)),
                'SESSION_NAME' => (string) config('app.session.name', 'daya_session'),
                'SESSION_LIFETIME' => (string) config('app.session.lifetime', 120),
                'SESSION_SECURE' => $sessionSecure ? '1' : '0',
                'SESSION_HTTP_ONLY' => config('app.session.http_only', true) ? '1' : '0',
                'SESSION_SAME_SITE' => (string) config('app.session.same_site', 'Lax'),
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => $dbHost,
                'DB_PORT' => (string) $dbPort,
                'DB_DATABASE' => $dbName,
                'DB_USERNAME' => $dbUser,
                'DB_PASSWORD' => $dbPass,
                'DB_CHARSET' => 'utf8mb4',
            ]);
        } catch (\Throwable $throwable) {
            $errors[] = 'Instalasi gagal: ' . $throwable->getMessage();

            return Response::html(render_layout('Installer', $this->renderForm($request, $errors)), 422);
        }

        Config::set('app.installed', true);
        $session->flash('status', [
            'type' => 'success',
            'message' => 'Instalasi berhasil dan environment tersimpan.',
        ]);

        $content = '<div class="card shadow-sm border-0"><div class="card-body p-4">'
            . '<span class="badge text-bg-success mb-3">Installer Selesai</span>'
            . '<h1 class="h4 mb-3">Project bootstrap berhasil dikonfigurasi.</h1>'
            . '<p class="text-secondary">File environment telah disimpan di <code>storage/config/.env</code>. '
            . 'Anda dapat kembali ke halaman utama untuk melanjutkan verifikasi fondasi aplikasi.</p>'
            . '<a class="btn btn-dark" href="/">Ke Home</a>'
            . '</div></div>';

        return Response::html(render_layout('Installer Selesai', $content));
    }

    private function renderForm(Request $request, array $errors = []): string
    {
        $csrfToken = $request->session()->csrfToken();
        $selectedEnv = (string) $request->input('app_env', config('app.env', 'development'));
        $selectedDebug = (string) $request->input('app_debug', config('app.debug', false) ? '1' : '0');

        $errorHtml = '';
        if ($errors !== []) {
            $items = '';
            foreach ($errors as $error) {
                $items .= '<li>' . e($error) . '</li>';
            }
            $errorHtml = '<div class="alert alert-danger"><ul class="mb-0">' . $items . '</ul></div>';
        }

        $installedNotice = config('app.installed', false)
            ? '<div class="alert alert-info">Aplikasi sudah pernah diinstal. Form ini tetap dapat dipakai untuk menulis ulang environment.</div>'
            : '';

        $developmentSelected = $selectedEnv === 'development' ? ' selected' : '';
        $stagingSelected = $selectedEnv === 'staging' ? ' selected' : '';
        $productionSelected = $selectedEnv === 'production' ? ' selected' : '';
        $debugOnSelected = $selectedDebug === '1' ? ' selected' : '';
        $debugOffSelected = $selectedDebug === '0' ? ' selected' : '';

        return $errorHtml . $installedNotice
            . '<div class="row justify-content-center"><div class="col-12 col-xl-8"><div class="card shadow-sm border-0">'
            . '<div class="card-body p-4 p-md-5"><span class="badge text-bg-dark mb-3">Installer</span>'
            . '<h1 class="h4 mb-3">Konfigurasi awal DAYA Platform</h1>'
            . '<p class="text-secondary mb-4">Installer ini menyimpan environment awal ke lokasi aman di luar web root publik.</p>'
            . '<form method="post" action="/install" class="row g-3">'
            . '<input type="hidden" name="_csrf_token" value="' . e($csrfToken) . '">'
            . '<div class="col-md-6"><label class="form-label">Nama Aplikasi</label>'
            . '<input type="text" name="app_name" class="form-control" value="' . e((string) $request->input('app_name', config('app.name', 'DAYA Platform'))) . '" required></div>'
            . '<div class="col-md-6"><label class="form-label">App URL</label>'
            . '<input type="url" name="app_url" class="form-control" value="' . e((string) $request->input('app_url', config('app.url', 'http://localhost'))) . '" required></div>'
            . '<div class="col-md-4"><label class="form-label">Environment</label>'
            . '<select name="app_env" class="form-select"><option value="development"' . $developmentSelected . '>development</option><option value="staging"' . $stagingSelected . '>staging</option><option value="production"' . $productionSelected . '>production</option></select></div>'
            . '<div class="col-md-4"><label class="form-label">Debug</label>'
            . '<select name="app_debug" class="form-select"><option value="1"' . $debugOnSelected . '>On</option><option value="0"' . $debugOffSelected . '>Off</option></select></div>'
            . '<div class="col-md-4"><label class="form-label">Timezone</label>'
            . '<input type="text" name="app_timezone" class="form-control" value="' . e((string) $request->input('app_timezone', config('app.timezone', 'Asia/Jakarta'))) . '"></div>'
            . '<div class="col-md-6"><label class="form-label">DB Host</label>'
            . '<input type="text" name="db_host" class="form-control" value="' . e((string) $request->input('db_host', env('DB_HOST', '127.0.0.1'))) . '" required></div>'
            . '<div class="col-md-6"><label class="form-label">DB Port</label>'
            . '<input type="number" name="db_port" class="form-control" value="' . e((string) $request->input('db_port', env('DB_PORT', '3306'))) . '" required></div>'
            . '<div class="col-md-6"><label class="form-label">DB Name</label>'
            . '<input type="text" name="db_database" class="form-control" value="' . e((string) $request->input('db_database', env('DB_DATABASE', ''))) . '" required></div>'
            . '<div class="col-md-6"><label class="form-label">DB Username</label>'
            . '<input type="text" name="db_username" class="form-control" value="' . e((string) $request->input('db_username', env('DB_USERNAME', ''))) . '" required></div>'
            . '<div class="col-12"><label class="form-label">DB Password</label>'
            . '<input type="password" name="db_password" class="form-control" value="' . e((string) $request->input('db_password', env('DB_PASSWORD', ''))) . '"></div>'
            . '<div class="col-12 d-flex flex-wrap gap-2 pt-2">'
            . '<button class="btn btn-dark" type="submit">Simpan Environment</button>'
            . '<a class="btn btn-outline-secondary" href="/">Kembali</a>'
            . '</div></form></div></div></div></div>';
    }

    private function writeEnvironment(array $values): void
    {
        $existingValues = $this->readEnvironmentFile();
        $mergedValues = array_merge($existingValues, $values);

        $content = '';
        foreach ($mergedValues as $key => $value) {
            $content .= $key . '=' . str_replace(["\r", "\n"], '', (string) $value) . PHP_EOL;
        }

        $file = storage_path('config/.env');
        $directory = dirname($file);
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Direktori konfigurasi environment tidak dapat dibuat.');
        }

        if (file_put_contents($file, $content, LOCK_EX) === false) {
            throw new RuntimeException('Gagal menulis file environment.');
        }
    }

    private function readEnvironmentFile(): array
    {
        $file = storage_path('config/.env');
        if (!is_file($file)) {
            return [];
        }

        $values = [];
        $lines = file($file, FILE_IGNORE_NEW_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $normalizedKey = trim($key);
            if ($normalizedKey === '') {
                continue;
            }

            $values[$normalizedKey] = trim($value, " \t\n\r\0\x0B\"");
        }

        return $values;
    }

    private function canAccessInstaller(): bool
    {
        if (!config('app.installed', false)) {
            return true;
        }

        $environment = (string) config('app.env', 'production');

        return $environment === 'development' || (bool) config('app.debug', false);
    }
}
