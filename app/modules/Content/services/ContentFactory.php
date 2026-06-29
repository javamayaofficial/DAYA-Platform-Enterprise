<?php

declare(strict_types=1);

namespace App\Modules\Content\Services;

use App\Core\Database;
use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Content\Models\ContentRepository;
use App\Modules\Content\Policies\ContentPolicy;
use PDO;

final class ContentFactory extends BaseService
{
    public function __construct(BaseModule $module)
    {
        parent::__construct($module);
    }

    public function pdo(): PDO
    {
        return Database::connection();
    }

    public function repository(): ContentRepository
    {
        return new ContentRepository($this->pdo());
    }

    public function validator(): ContentValidator
    {
        return new ContentValidator(
            array_keys((array) $this->config('content_types', [])),
            array_keys((array) $this->config('statuses', [])),
            array_keys((array) $this->config('review_statuses', [])),
            array_keys((array) $this->config('access_policies', [])),
            array_keys((array) $this->config('visibility_options', []))
        );
    }

    public function policy(): ContentPolicy
    {
        return new ContentPolicy();
    }

    public function service(): ContentService
    {
        return new ContentService($this->module(), $this->repository());
    }
}
