<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class RbacMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly array $requiredRoles = [])
    {
    }

    public function handle(Request $request, callable $next): Response
    {
        $auth = $request->session()->get('auth', []);
        $roles = is_array($auth['roles'] ?? null) ? $auth['roles'] : [];

        if ($this->requiredRoles !== [] && array_intersect($this->requiredRoles, $roles) === []) {
            return Response::html(render_layout('Akses Ditolak', '<div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-danger mb-3">403</span><h1 class="h4 mb-3">Role tidak memenuhi syarat.</h1><p class="text-secondary">RBAC middleware bootstrap sudah aktif dan menolak akses yang tidak memiliki role yang dibutuhkan.</p><a class="btn btn-dark" href="/">Kembali</a></div></div>'), 403);
        }

        return $next($request);
    }
}
