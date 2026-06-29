<?php

declare(strict_types=1);

namespace App\Modules\Collection\Services;

use App\Core\Database;
use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Collection\Models\CollectionRepository;
use App\Modules\Collection\Policies\CollectionPolicy;
use PDO;

final class CollectionFactory extends BaseService
{
    public function __construct(BaseModule $module)
    {
        parent::__construct($module);
    }

    public function pdo(): PDO
    {
        return Database::connection();
    }

    public function repository(): CollectionRepository
    {
        return new CollectionRepository($this->pdo());
    }

    public function validator(): CollectionValidator
    {
        return new CollectionValidator(
            array_keys((array) $this->config('statuses', [])),
            array_keys((array) $this->config('visibility_options', []))
        );
    }

    public function policy(): CollectionPolicy
    {
        return new CollectionPolicy();
    }

    public function service(): CollectionService
    {
        return new CollectionService($this->module(), $this->repository(), $this->validator());
    }
}
