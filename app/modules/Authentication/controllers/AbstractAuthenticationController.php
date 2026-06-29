<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Controllers;

use App\Core\Http\Response;
use App\Core\Modular\BaseController;
use App\Modules\Authentication\AuthenticationModule;
use App\Modules\Authentication\Responses\AuthResponse;
use App\Modules\Authentication\Services\AuthenticationFactory;

abstract class AbstractAuthenticationController extends BaseController
{
    protected AuthenticationFactory $factory;

    public function __construct()
    {
        $module = AuthenticationModule::instance();
        parent::__construct($module);
        $this->factory = $module->factory();
    }

    protected function render(string $view, string $title, array $data = [], int $statusCode = 200): Response
    {
        return AuthResponse::view($view, $title, $data, $statusCode);
    }
}
