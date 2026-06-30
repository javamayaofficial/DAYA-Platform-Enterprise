<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class PermissionMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly string $permission)
    {
    }

    public function handle(Request $request, callable $next): Response
    {
        $auth = $request->session()->get('auth', []);
        $permissions = is_array($auth['permissions'] ?? null) ? $auth['permissions'] : [];
        $roles = is_array($auth['roles'] ?? null) ? $auth['roles'] : [];

        if (!in_array('super_admin', $roles, true) && !in_array($this->permission, $permissions, true)) {
            return Response::html(render_layout('Akses Ditolak', '<div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-danger mb-3">403</span><h1 class="h4 mb-3">Permission tidak cukup.</h1><p class="text-secondary">Akses ditolak oleh middleware permission.</p><a class="btn btn-dark" href="/auth/security">Kembali</a></div></div>'), 403);
        }

        return $next($request);
    }
}
