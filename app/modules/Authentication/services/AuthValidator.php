<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

final class AuthValidator
{
    public function validateRegistration(array $input): array
    {
        $errors = [];
        $name = trim((string) ($input['name'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');
        $passwordConfirmation = (string) ($input['password_confirmation'] ?? '');

        if ($name === '' || mb_strlen($name) < 3) {
            $errors['name'] = 'Nama minimal 3 karakter.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password minimal 8 karakter.';
        }
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Password harus mengandung huruf besar dan angka.';
        }
        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = 'Konfirmasi password tidak sama.';
        }

        return ['errors' => $errors, 'data' => compact('name', 'email', 'password')];
    }

    public function validateLogin(array $input): array
    {
        $errors = [];
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['password'] ?? '');
        $remember = (string) ($input['remember_me'] ?? '0') === '1';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid.';
        }
        if ($password === '') {
            $errors['password'] = 'Password wajib diisi.';
        }

        return ['errors' => $errors, 'data' => compact('email', 'password', 'remember')];
    }

    public function validateForgotPassword(array $input): array
    {
        $errors = [];
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email tidak valid.';
        }

        return ['errors' => $errors, 'data' => compact('email')];
    }

    public function validateResetPassword(array $input): array
    {
        $errors = [];
        $token = trim((string) ($input['token'] ?? ''));
        $password = (string) ($input['password'] ?? '');
        $passwordConfirmation = (string) ($input['password_confirmation'] ?? '');

        if ($token === '') {
            $errors['token'] = 'Token reset tidak valid.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password minimal 8 karakter.';
        }
        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Password harus mengandung huruf besar dan angka.';
        }
        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = 'Konfirmasi password tidak sama.';
        }

        return ['errors' => $errors, 'data' => compact('token', 'password')];
    }

    public function validateRoleAssignment(array $input): array
    {
        $roles = $input['roles'] ?? [];
        if (!is_array($roles)) {
            $roles = [];
        }

        return ['errors' => [], 'data' => ['roles' => array_values(array_unique(array_map('strval', $roles)))]];
    }
}
