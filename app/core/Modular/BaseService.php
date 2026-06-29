<?php

declare(strict_types=1);

namespace App\Core\Modular;

abstract class BaseService
{
    public function __construct(protected readonly BaseModule $module)
    {
    }

    protected function module(): BaseModule
    {
        return $this->module;
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->module->config($key, $default);
    }
}
