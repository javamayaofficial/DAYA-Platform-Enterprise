<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Responses;

use App\Core\Http\Response;
use App\Core\Modular\BaseResponse;
use App\Modules\Authentication\AuthenticationModule;

final class AuthResponse extends BaseResponse
{
    public static function view(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        $module = AuthenticationModule::instance();
        $viewPath = str_replace('\\', '/', $module->viewPath(trim($view, '/') . '.php'));
        $normalizedBasePath = str_replace('\\', '/', base_path()) . '/';

        if (str_starts_with($viewPath, $normalizedBasePath)) {
            $viewPath = substr($viewPath, strlen($normalizedBasePath));
        }

        return parent::html(render_layout($title, render_view($viewPath, $data)), $statusCode, self::noStoreHeaders());
    }

    public static function redirect(string $location, int $statusCode = 302, array $headers = []): Response
    {
        return Response::redirect($location, $statusCode, array_merge($headers, self::noStoreHeaders()));
    }

    private static function noStoreHeaders(): array
    {
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
    }
}
