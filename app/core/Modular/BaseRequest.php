<?php

declare(strict_types=1);

namespace App\Core\Modular;

use App\Core\Http\Request;
use App\Core\Http\SessionManager;

class BaseRequest
{
    public function __construct(protected readonly Request $request)
    {
    }

    public static function from(Request $request): static
    {
        return new static($request);
    }

    public function raw(): Request
    {
        return $this->request;
    }

    public function all(): array
    {
        return $this->request->all();
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->request->input($key, $default);
    }

    public function string(string $key, string $default = ''): string
    {
        return trim((string) $this->input($key, $default));
    }

    public function integer(string $key, int $default = 0): int
    {
        return (int) $this->input($key, $default);
    }

    public function boolean(string $key, bool $default = false): bool
    {
        $value = $this->input($key, $default ? '1' : '0');

        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->request->query($key, $default);
    }

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->request->route($key, $default);
    }

    public function session(): SessionManager
    {
        return $this->request->session();
    }
}
