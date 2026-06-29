<?php

declare(strict_types=1);

namespace App\Modules\Creator\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Modular\BaseController;
use App\Modules\Creator\CreatorModule;
use App\Modules\Creator\Responses\CreatorResponse;
use App\Modules\Creator\Services\CreatorFactory;

abstract class AbstractCreatorController extends BaseController
{
    protected CreatorFactory $factory;

    public function __construct()
    {
        $module = CreatorModule::instance();
        parent::__construct($module);
        $this->factory = $module->factory();
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        return CreatorResponse::view($view, $title, $data, $statusCode);
    }

    protected function auth(Request $request, array $default = []): array
    {
        $auth = $request->session()->get('auth', $default);

        return is_array($auth) ? $auth : $default;
    }
}
