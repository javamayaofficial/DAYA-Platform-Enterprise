<?php

declare(strict_types=1);

namespace App\Modules\Collection;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;

final class CollectionModule extends BaseModule
{
    private static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('Collection', base_path('app/modules/Collection'));
    }

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function factory(): Services\CollectionFactory
    {
        return new Services\CollectionFactory($this);
    }

    public function boot(Request $request, Logger $logger): void
    {
        // Collection module currently has no global request bootstrap.
    }
}
