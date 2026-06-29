<?php

declare(strict_types=1);

namespace App\Core\Modular;

use App\Core\Http\Response;

class BaseResponse
{
    public static function html(string $body, int $statusCode = 200, array $headers = []): Response
    {
        return Response::html($body, $statusCode, $headers);
    }

    public static function json(array $payload, int $statusCode = 200, array $headers = []): Response
    {
        return Response::json($payload, $statusCode, $headers);
    }

    public static function redirect(string $location, int $statusCode = 302): Response
    {
        return Response::redirect($location, $statusCode);
    }

    public static function view(string $viewPath, string $title, array $data = [], int $statusCode = 200): Response
    {
        $content = render_view($viewPath, $data);

        return self::html(render_layout($title, $content), $statusCode);
    }
}
