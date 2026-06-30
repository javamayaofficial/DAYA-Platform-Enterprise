<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Installer;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\RbacMiddleware;
use App\Middleware\RateLimitMiddleware;

$router->get('/', function (Request $request): Response {
    $session = $request->session();
    $counter = (int) $session->get('bootstrap.counter', 0) + 1;
    $session->set('bootstrap.counter', $counter);
    $csrfToken = $session->csrfToken();
    $auth = $session->get('auth', []);
    $isInstalled = (bool) config('app.installed', false);
    $dbConfigured = Database::hasConfiguration();
    $dbReachable = false;

    if ($dbConfigured) {
        try {
            Database::connection();
            $dbReachable = true;
        } catch (Throwable) {
            $dbReachable = false;
        }
    }

    $flash = $session->pullFlash('status');
    $roles = isset($auth['roles']) && is_array($auth['roles']) ? implode(', ', $auth['roles']) : '-';
    $isDevelopment = (string) config('app.env', 'production') === 'development';
    $exposeDiagnostics = $isDevelopment || (bool) config('app.debug', false);

    $flashHtml = '';
    if (is_array($flash) && isset($flash['message'], $flash['type'])) {
        $flashHtml = '<div class="alert alert-' . e((string) $flash['type']) . ' mb-4" data-autohide="true">'
            . e((string) $flash['message'])
            . '</div>';
    }

    $diagnosticCardsHtml = '';
    if ($exposeDiagnostics) {
        $diagnosticCardsHtml = '<div class="row g-3 mb-4">'
            . '<div class="col-md-6"><div class="status-card"><div class="status-label">Environment</div><div class="status-value">' . e((string) config('app.env')) . '</div></div></div>'
            . '<div class="col-md-6"><div class="status-card"><div class="status-label">PHP Version</div><div class="status-value">' . e(PHP_VERSION) . '</div></div></div>'
            . '<div class="col-md-6"><div class="status-card"><div class="status-label">Session Counter</div><div class="status-value">' . e((string) $counter) . '</div></div></div>'
            . '<div class="col-md-6"><div class="status-card"><div class="status-label">CSRF Token</div><div class="status-value small text-break">' . e($csrfToken) . '</div></div></div>'
            . '</div>';
    }

    $actionButtonsHtml = '<div class="d-flex flex-wrap gap-2 mb-4">';
    if ($exposeDiagnostics) {
        $actionButtonsHtml .= '<a href="/health" class="btn btn-dark">Cek Health</a>';
    }
    if (!$isInstalled || $exposeDiagnostics) {
        $actionButtonsHtml .= '<a href="/install" class="btn btn-outline-dark">Buka Installer</a>';
    }
    if ($isDevelopment) {
        $actionButtonsHtml .= '<a href="/bootstrap/protected" class="btn btn-outline-secondary">Tes Protected Route</a>';
    }
    $actionButtonsHtml .= '</div>';

    $demoSessionHtml = '';
    if ($isDevelopment) {
        $demoSessionHtml = '<div class="border rounded-4 p-3 bg-light-subtle">'
            . '<div class="fw-semibold mb-3">Demo Session & Middleware</div>'
            . '<div class="d-flex flex-wrap gap-2">'
            . '<form action="/bootstrap/mock-login" method="post" class="d-inline"><input type="hidden" name="_csrf_token" value="' . e($csrfToken) . '"><button class="btn btn-sm btn-success" type="submit">Mock Login Admin</button></form>'
            . '<form action="/bootstrap/mock-logout" method="post" class="d-inline"><input type="hidden" name="_csrf_token" value="' . e($csrfToken) . '"><button class="btn btn-sm btn-outline-danger" type="submit">Mock Logout</button></form>'
            . '<form action="/bootstrap/session/regenerate" method="post" class="d-inline"><input type="hidden" name="_csrf_token" value="' . e($csrfToken) . '"><button class="btn btn-sm btn-outline-primary" type="submit">Regenerate Session</button></form>'
            . '</div></div>';
    }

    $statusListHtml = '<li class="list-group-item d-flex justify-content-between px-0"><span>Installed</span><strong>' . ($isInstalled ? 'Yes' : 'No') . '</strong></li>';
    if ($exposeDiagnostics) {
        $statusListHtml .= '<li class="list-group-item d-flex justify-content-between px-0"><span>DB Configured</span><strong>' . ($dbConfigured ? 'Yes' : 'No') . '</strong></li>'
            . '<li class="list-group-item d-flex justify-content-between px-0"><span>DB Reachable</span><strong>' . ($dbReachable ? 'Yes' : 'No') . '</strong></li>'
            . '<li class="list-group-item d-flex justify-content-between px-0"><span>Session Name</span><strong>' . e(session_name()) . '</strong></li>';
    }
    if ($isDevelopment) {
        $statusListHtml .= '<li class="list-group-item d-flex justify-content-between px-0"><span>Mock User</span><strong>' . e((string) ($auth['user_id'] ?? '-')) . '</strong></li>'
            . '<li class="list-group-item d-flex justify-content-between px-0"><span>Mock Roles</span><strong>' . e($roles) . '</strong></li>';
    }

    $content = $flashHtml
        . '<div class="row g-4">'
        . '<div class="col-12 col-xl-8">'
        . '<div class="card shadow-sm border-0 h-100"><div class="card-body p-4">'
        . '<span class="badge text-bg-dark mb-3">Phase 1 Ready</span>'
        . '<h1 class="h3 mb-3">Fondasi aplikasi DAYA Platform sudah aktif.</h1>'
        . '<p class="text-secondary mb-4">Halaman ini membuktikan front controller, autoload, router, config, session, logging, database bootstrap, installer, dan middleware sudah berjalan tanpa membuat modul bisnis.</p>'
        . $diagnosticCardsHtml
        . $actionButtonsHtml
        . $demoSessionHtml
        . '</div></div></div>'
        . '<div class="col-12 col-xl-4">'
        . '<div class="card shadow-sm border-0 mb-4"><div class="card-body p-4"><h2 class="h5 mb-3">Status Sistem</h2><ul class="list-group list-group-flush small">'
        . $statusListHtml
        . '</ul></div></div>'
        . '<div class="card shadow-sm border-0"><div class="card-body p-4"><h2 class="h5 mb-3">Catatan Bootstrap</h2><ul class="small text-secondary ps-3 mb-0">'
        . '<li>Tidak ada modul bisnis yang dibuat.</li>'
        . '<li>Middleware Auth, RBAC, CSRF, dan RateLimit sudah disiapkan.</li>'
        . '<li>Installer menyimpan environment ke storage/config/.env.</li>'
        . '<li>Error dan exception dicatat ke storage/logs.</li>'
        . '</ul></div></div></div></div>';

    return Response::html(render_layout('Bootstrap Home', $content));
});

$router->get('/health', function (): Response {
    $isDevelopment = (string) config('app.env', 'production') === 'development';
    $exposeDiagnostics = $isDevelopment || (bool) config('app.debug', false);
    $database = [
        'configured' => Database::hasConfiguration(),
        'reachable' => false,
    ];

    if ($database['configured']) {
        try {
            Database::connection();
            $database['reachable'] = true;
        } catch (Throwable) {
            $database['reachable'] = false;
        }
    }

    $payload = [
        'success' => true,
        'data' => [
            'status' => $database['reachable'] || !$database['configured'] ? 'ok' : 'degraded',
            'time' => date(DATE_ATOM),
        ],
        'message' => 'OK',
    ];

    if ($exposeDiagnostics) {
        $payload['data']['app'] = [
            'name' => config('app.name'),
            'env' => config('app.env'),
            'debug' => config('app.debug'),
            'installed' => config('app.installed'),
        ];
        $payload['data']['php'] = [
            'version' => PHP_VERSION,
            'sapi' => PHP_SAPI,
        ];
        $payload['data']['database'] = $database;
    }

    return Response::json($payload);
});

$router->match(['GET', 'POST'], '/install', function (Request $request): Response {
    $installer = new Installer();

    return $installer->handle($request);
});

if ((string) config('app.env', 'production') === 'development') {
    $router->post('/bootstrap/mock-login', function (Request $request): Response {
        $request->session()->set('auth', [
            'user_id' => 1,
            'roles' => ['admin'],
            'name' => 'Bootstrap Admin',
        ]);
        $request->session()->flash('status', [
            'type' => 'success',
            'message' => 'Mock admin session dibuat.',
        ]);

        return Response::redirect('/');
    }, [CsrfMiddleware::class]);

    $router->post('/bootstrap/mock-logout', function (Request $request): Response {
        $request->session()->remove('auth');
        $request->session()->flash('status', [
            'type' => 'warning',
            'message' => 'Mock session dihapus.',
        ]);

        return Response::redirect('/');
    }, [CsrfMiddleware::class]);

    $router->post('/bootstrap/session/regenerate', function (Request $request): Response {
        $request->session()->regenerate();
        $request->session()->flash('status', [
            'type' => 'info',
            'message' => 'Session ID berhasil diregenerasi.',
        ]);

        return Response::redirect('/');
    }, [CsrfMiddleware::class, [RateLimitMiddleware::class, 'session-regenerate', 20, 60]]);

    $router->get('/bootstrap/protected', function (Request $request): Response {
        $auth = $request->session()->get('auth', []);
        $roles = implode(', ', $auth['roles'] ?? []);

        $content = '<div class="card shadow-sm border-0"><div class="card-body p-4">'
            . '<span class="badge text-bg-success mb-3">Protected Route</span>'
            . '<h1 class="h4 mb-3">Middleware Auth dan RBAC aktif.</h1>'
            . '<p class="text-secondary">Halaman ini hanya bisa diakses bila session mock login tersedia dan memiliki role <code>admin</code>.</p>'
            . '<ul class="list-group list-group-flush small mb-4">'
            . '<li class="list-group-item px-0 d-flex justify-content-between"><span>User ID</span><strong>' . e((string) ($auth['user_id'] ?? '-')) . '</strong></li>'
            . '<li class="list-group-item px-0 d-flex justify-content-between"><span>Name</span><strong>' . e((string) ($auth['name'] ?? '-')) . '</strong></li>'
            . '<li class="list-group-item px-0 d-flex justify-content-between"><span>Roles</span><strong>' . e($roles) . '</strong></li>'
            . '</ul>'
            . '<a href="/" class="btn btn-dark">Kembali</a>'
            . '</div></div>';

        return Response::html(render_layout('Protected Bootstrap Route', $content));
    }, [AuthMiddleware::class, [RbacMiddleware::class, ['admin']], [RateLimitMiddleware::class, 'protected-bootstrap', 60, 60]]);
}

$moduleManager->registerRoutes($router);
