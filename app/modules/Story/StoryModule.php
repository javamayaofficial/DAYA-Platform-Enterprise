<?php

declare(strict_types=1);

namespace App\Modules\Story;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;

final class StoryModule extends BaseModule
{
    private static ?self $instance = null;

    private function __construct()
    {
        parent::__construct('Story', base_path('app/modules/Story'));
    }

    public static function instance(): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function factory(): Services\StoryFactory
    {
        return new Services\StoryFactory($this);
    }

    public function boot(Request $request, Logger $logger): void
    {
        // Story module currently has no global request bootstrap.
    }
}
