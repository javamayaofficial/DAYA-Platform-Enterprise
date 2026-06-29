<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class RateLimitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly string $key = 'global',
        private readonly int $maxAttempts = 60,
        private readonly int $windowSeconds = 60
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        $sessionKey = '_rate_limit.' . $this->key . '.' . md5($request->ip());
        $bucket = $request->session()->get($sessionKey, ['count' => 0, 'expires_at' => time() + $this->windowSeconds]);

        if (($bucket['expires_at'] ?? 0) < time()) {
            $bucket = ['count' => 0, 'expires_at' => time() + $this->windowSeconds];
        }

        $bucket['count'] = (int) ($bucket['count'] ?? 0) + 1;
        $request->session()->set($sessionKey, $bucket);

        if ($bucket['count'] > $this->maxAttempts) {
            return Response::html(render_layout('Rate Limit', '<div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-warning mb-3">429</span><h1 class="h4 mb-3">Terlalu banyak permintaan.</h1><p class="text-secondary">Middleware rate limit bootstrap sedang melindungi endpoint ini.</p><a class="btn btn-dark" href="/">Kembali</a></div></div>'), 429);
        }

        return $next($request);
    }
}
