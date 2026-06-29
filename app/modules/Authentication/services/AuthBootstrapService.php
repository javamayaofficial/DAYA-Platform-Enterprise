<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

final class AuthBootstrapService
{
    public static function boot(Request $request, Logger $logger): void
    {
        ModuleBootstrap::boot($request, $logger);
    }
}
