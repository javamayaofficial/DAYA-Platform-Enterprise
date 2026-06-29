<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\AuthenticationModule;
use App\Core\Http\Request;
use App\Core\Logging\Logger;

final class ModuleBootstrap
{
    public static function boot(Request $request, Logger $logger): void
    {
        AuthenticationModule::instance()->boot($request, $logger);
    }
}
