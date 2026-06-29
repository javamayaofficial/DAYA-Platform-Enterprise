<?php

declare(strict_types=1);

namespace App\Modules\Story\Services;

use App\Core\Database;
use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Story\Models\StoryRepository;
use App\Modules\Story\Policies\StoryPolicy;
use PDO;

final class StoryFactory extends BaseService
{
    public function __construct(BaseModule $module)
    {
        parent::__construct($module);
    }

    public function pdo(): PDO
    {
        return Database::connection();
    }

    public function repository(): StoryRepository
    {
        return new StoryRepository($this->pdo());
    }

    public function validator(): StoryValidator
    {
        return new StoryValidator(
            array_keys((array) $this->config('statuses', [])),
            array_keys((array) $this->config('visibility_options', [])),
            array_keys((array) $this->config('language_options', []))
        );
    }

    public function policy(): StoryPolicy
    {
        return new StoryPolicy();
    }

    public function service(): StoryService
    {
        return new StoryService($this->module(), $this->repository(), $this->validator());
    }
}
