<?php

declare(strict_types=1);

namespace App\Modules\Creator\Services;

use App\Core\Database;
use App\Core\Modular\BaseModule;
use App\Core\Modular\BaseService;
use App\Modules\Authentication\Models\RoleRepository;
use App\Modules\Creator\Models\CreatorRepository;
use App\Modules\Creator\Policies\CreatorPolicy;
use PDO;

final class CreatorFactory extends BaseService
{
    public function __construct(BaseModule $module)
    {
        parent::__construct($module);
    }

    public function pdo(): PDO
    {
        return Database::connection();
    }

    public function repository(): CreatorRepository
    {
        return new CreatorRepository($this->pdo());
    }

    public function roleRepository(): RoleRepository
    {
        return new RoleRepository($this->pdo());
    }

    public function validator(): CreatorValidator
    {
        return new CreatorValidator(
            (array) $this->config('categories', []),
            array_keys((array) $this->config('social_platforms', [])),
            array_keys((array) $this->config('creator_types', [])),
            array_keys((array) $this->config('creator_levels', [])),
            array_keys((array) $this->config('verification_statuses', [])),
            array_keys((array) $this->config('portfolio_types', [])),
            (array) $this->config('badge_catalog', [])
        );
    }

    public function policy(): CreatorPolicy
    {
        return new CreatorPolicy();
    }

    public function service(): CreatorService
    {
        return new CreatorService($this->module(), $this->repository(), $this->roleRepository());
    }
}
