<?php

declare(strict_types=1);

namespace App\Modules\Story\Responses;

use App\Core\Http\Response;
use App\Core\Modular\BaseResponse;
use App\Modules\Story\StoryModule;

final class StoryResponse extends BaseResponse
{
    public static function view(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        $module = StoryModule::instance();
        $viewPath = str_replace('\\', '/', $module->viewPath(trim($view, '/') . '.php'));
        $normalizedBasePath = str_replace('\\', '/', base_path()) . '/';

        if (str_starts_with($viewPath, $normalizedBasePath)) {
            $viewPath = substr($viewPath, strlen($normalizedBasePath));
        }

        return parent::view($viewPath, $title, $data, $statusCode);
    }
}
