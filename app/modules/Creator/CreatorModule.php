<?php

declare(strict_types=1);

namespace App\Modules\Creator;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;

final class CreatorModule extends BaseModule
{
    private static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('Creator', base_path('app/modules/Creator'));
    }

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function factory(): Services\CreatorFactory
    {
        return new Services\CreatorFactory($this);
    }

    public function boot(Request $request, Logger $logger): void
    {
        // Creator module currently has no global request bootstrap.
    }
}
