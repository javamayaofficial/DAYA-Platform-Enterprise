<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Contracts\MiddlewareInterface;
use App\Core\Http\Request;
use App\Core\Http\Response;
use RuntimeException;

final class MiddlewareDispatcher
{
    public function handle(Request $request, array $middlewareStack, callable $destination): Response
    {
        $runner = array_reduce(
            array_reverse($middlewareStack),
            function (callable $next, mixed $middlewareDefinition): callable {
                return function (Request $request) use ($middlewareDefinition, $next): Response {
                    $middleware = $this->resolve($middlewareDefinition);

                    return $middleware->handle($request, $next);
                };
            },
            $destination
        );

        return $runner($request);
    }

    private function resolve(mixed $middlewareDefinition): MiddlewareInterface
    {
        if ($middlewareDefinition instanceof MiddlewareInterface) {
            return $middlewareDefinition;
        }

        if (is_string($middlewareDefinition) && class_exists($middlewareDefinition)) {
            return new $middlewareDefinition();
        }

        if (is_array($middlewareDefinition) && isset($middlewareDefinition[0]) && is_string($middlewareDefinition[0])) {
            $className = array_shift($middlewareDefinition);

            return new $className(...$middlewareDefinition);
        }

        throw new RuntimeException('Middleware definition tidak valid.');
    }
}
