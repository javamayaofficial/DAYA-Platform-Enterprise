<?php

declare(strict_types=1);

namespace App\Core\Modular;

use App\Core\Http\Request;
use App\Core\Logging\Logger;

abstract class BaseModule
{
    public function __construct(
        private readonly string $name,
        private readonly string $path
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function key(): string
    {
        return strtolower($this->name);
    }

    public function path(string $append = ''): string
    {
        if ($append === '') {
            return $this->path;
        }

        return rtrim($this->path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($append, '\\/');
    }

    public function viewPath(string $view): string
    {
        return $this->path('views/' . ltrim($view, '\\/'));
    }

    public function config(string $key, mixed $default = null): mixed
    {
        $configKey = $this->key();
        if ($key === '') {
            return config($configKey, $default);
        }

        return config($configKey . '.' . $key, $default);
    }

    public function boot(Request $request, Logger $logger): void
    {
    }
}
