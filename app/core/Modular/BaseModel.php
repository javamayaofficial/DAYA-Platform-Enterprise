<?php

declare(strict_types=1);

namespace App\Core\Modular;

abstract class BaseModel
{
    protected static function value(array $row, string $key, mixed $default = null): mixed
    {
        return $row[$key] ?? $default;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
