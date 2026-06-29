<?php

declare(strict_types=1);

namespace App\Modules\Content;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;

final class ContentModule extends BaseModule
{
    private static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('Content', base_path('app/modules/Content'));
    }

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function factory(): Services\ContentFactory
    {
        return new Services\ContentFactory($this);
    }

    public function boot(Request $request, Logger $logger): void
    {
        // Content module currently has no global request bootstrap.
    }
}
