<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class CsrfMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if ($request->method() === 'GET') {
            return $next($request);
        }

        $token = (string) $request->input('_csrf_token', $request->header('X-CSRF-Token', ''));
        if (!hash_equals($request->session()->csrfToken(), $token)) {
            return Response::html(render_layout('CSRF Invalid', '<div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-danger mb-3">419</span><h1 class="h4 mb-3">Token CSRF tidak valid.</h1><p class="text-secondary">Permintaan dihentikan oleh middleware CSRF bootstrap.</p><a class="btn btn-dark" href="/">Kembali</a></div></div>'), 419);
        }

        return $next($request);
    }
}
