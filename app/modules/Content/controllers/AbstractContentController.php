<?php

declare(strict_types=1);

namespace App\Modules\Content\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Modular\BaseController;
use App\Modules\Content\ContentModule;
use App\Modules\Content\Responses\ContentResponse;
use App\Modules\Content\Services\ContentFactory;

abstract class AbstractContentController extends BaseController
{
    protected ContentFactory $factory;

    public function __construct()
    {
        $module = ContentModule::instance();
        parent::__construct($module);
        $this->factory = $module->factory();
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        return ContentResponse::view($view, $title, $data, $statusCode);
    }

    protected function auth(Request $request, array $default = []): array
    {
        $auth = $request->session()->get('auth', $default);

        return is_array($auth) ? $auth : $default;
    }
}
