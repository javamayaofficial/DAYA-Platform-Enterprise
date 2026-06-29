<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Requests;

use App\Core\Modular\BaseRequest;

final class AuthRequest extends BaseRequest
{
    public function email(): string
    {
        return strtolower($this->string('email'));
    }

    public function password(): string
    {
        return (string) $this->input('password', '');
    }

    public function rememberMe(): bool
    {
        return $this->boolean('remember_me');
    }

    public function token(): string
    {
        return $this->string('token');
    }

    public function userIdFromRoute(): int
    {
        return (int) $this->route('id', 0);
    }
}
