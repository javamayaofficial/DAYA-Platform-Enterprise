<?php

declare(strict_types=1);

namespace App\Core\Modular;

use App\Core\Http\Request;
use App\Core\Http\Response;

abstract class BaseController
{
    public function __construct(protected readonly BaseModule $module)
    {
    }

    protected function module(): BaseModule
    {
        return $this->module;
    }

    protected function request(Request $request): BaseRequest
    {
        return BaseRequest::from($request);
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        $relativeView = trim(str_replace('\\', '/', $view), '/');
        $viewPath = str_replace('\\', '/', $this->module->viewPath($relativeView . '.php'));
        $normalizedPath = str_replace('\\', '/', base_path());

        if (str_starts_with($viewPath, $normalizedPath . '/')) {
            $viewPath = substr($viewPath, strlen($normalizedPath . '/'));
        }

        return BaseResponse::view($viewPath, $title, $data, $statusCode);
    }
}
