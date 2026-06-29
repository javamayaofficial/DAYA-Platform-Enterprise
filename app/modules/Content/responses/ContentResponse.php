<?php

declare(strict_types=1);

namespace App\Modules\Content\Responses;

use App\Core\Http\Response;
use App\Core\Modular\BaseResponse;
use App\Modules\Content\ContentModule;

final class ContentResponse extends BaseResponse
{
    public static function view(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        $module = ContentModule::instance();
        $viewPath = str_replace('\\', '/', $module->viewPath(trim($view, '/') . '.php'));
        $normalizedBasePath = str_replace('\\', '/', base_path()) . '/';

        if (str_starts_with($viewPath, $normalizedBasePath)) {
            $viewPath = substr($viewPath, strlen($normalizedBasePath));
        }

        return parent::view($viewPath, $title, $data, $statusCode);
    }
}
