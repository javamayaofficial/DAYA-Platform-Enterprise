<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $auth = $request->session()->get('auth');
        if (!is_array($auth) || !isset($auth['user_id'])) {
            return Response::html(render_layout('Akses Ditolak', '<div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-warning mb-3">401</span><h1 class="h4 mb-3">Autentikasi dibutuhkan.</h1><p class="text-secondary">Middleware Auth bootstrap aktif, tetapi session user belum tersedia.</p><a class="btn btn-dark" href="/">Kembali</a></div></div>'), 401);
        }

        return $next($request);
    }
}
