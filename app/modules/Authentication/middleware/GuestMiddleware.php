<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Middleware;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;

final class GuestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $auth = $request->session()->get('auth', []);
        if (is_array($auth) && isset($auth['user_id'])) {
            return Response::redirect('/auth/security');
        }

        return $next($request);
    }
}
