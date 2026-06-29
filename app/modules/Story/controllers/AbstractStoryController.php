<?php

declare(strict_types=1);

namespace App\Modules\Story\Controllers;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Modular\BaseController;
use App\Modules\Story\Responses\StoryResponse;
use App\Modules\Story\Services\StoryFactory;
use App\Modules\Story\StoryModule;

abstract class AbstractStoryController extends BaseController
{
    protected StoryFactory $factory;

    public function __construct()
    {
        $module = StoryModule::instance();
        parent::__construct($module);
        $this->factory = $module->factory();
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        return StoryResponse::view($view, $title, $data, $statusCode);
    }

    protected function auth(Request $request, array $default = []): array
    {
        $auth = $request->session()->get('auth', $default);

        return is_array($auth) ? $auth : $default;
    }
}
