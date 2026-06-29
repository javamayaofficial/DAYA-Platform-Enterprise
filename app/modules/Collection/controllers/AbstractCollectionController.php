<?php

declare(strict_types=1);

namespace App\Modules\Collection\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Modular\BaseController;
use App\Modules\Collection\CollectionModule;
use App\Modules\Collection\Responses\CollectionResponse;
use App\Modules\Collection\Services\CollectionFactory;

abstract class AbstractCollectionController extends BaseController
{
    protected CollectionFactory $factory;

    public function __construct()
    {
        $module = CollectionModule::instance();
        parent::__construct($module);
        $this->factory = $module->factory();
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        return CollectionResponse::view($view, $title, $data, $statusCode);
    }

    protected function auth(Request $request, array $default = []): array
    {
        $auth = $request->session()->get('auth', $default);

        return is_array($auth) ? $auth : $default;
    }
}
