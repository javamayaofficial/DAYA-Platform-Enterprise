<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\HttpException;
use App\Core\Http\Request;
use App\Core\Http\Response;
use RuntimeException;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler, array $middleware = []): void
    {
        $this->add(['GET'], $path, $handler, $middleware);
    }

    public function post(string $path, callable $handler, array $middleware = []): void
    {
        $this->add(['POST'], $path, $handler, $middleware);
    }

    public function match(array $methods, string $path, callable $handler, array $middleware = []): void
    {
        $this->add($methods, $path, $handler, $middleware);
    }

    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if (!in_array($request->method(), $route['methods'], true)) {
                continue;
            }

            $params = $this->matchRoute($route['path'], $request->path());
            if ($params === null) {
                continue;
            }

            $request->setRouteParams($params);
            $dispatcher = new MiddlewareDispatcher();

            return $dispatcher->handle($request, $route['middleware'], function (Request $request) use ($route): Response {
                $response = ($route['handler'])($request);
                if (!$response instanceof Response) {
                    throw new RuntimeException('Route handler harus mengembalikan Response.');
                }

                return $response;
            });
        }

        throw new HttpException(404, 'Halaman tidak ditemukan.');
    }

    private function add(array $methods, string $path, callable $handler, array $middleware): void
    {
        $this->routes[] = [
            'methods' => array_map('strtoupper', $methods),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    private function matchRoute(string $routePath, string $requestPath): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#';
        $normalizedRequestPath = rtrim($requestPath, '/') === '' ? '/' : rtrim($requestPath, '/');

        if (!preg_match($pattern, $normalizedRequestPath, $matches)) {
            return null;
        }

        return array_filter($matches, static fn ($key): bool => is_string($key), ARRAY_FILTER_USE_KEY);
    }
}
