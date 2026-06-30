<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use RuntimeException;
use Throwable;

final class AuthMailService
{
    public function sendVerification(string $email, string $name, string $verificationUrl): void
    {
        $subject = 'Verifikasi Email DAYA Platform';
        $body = "Halo {$name},\n\nSilakan verifikasi email Anda melalui tautan berikut:\n{$verificationUrl}\n\nTautan ini memiliki masa berlaku terbatas.";
        $this->dispatchSafely($email, $subject, $body, 'verification');
    }

    public function sendPasswordReset(string $email, string $name, string $resetUrl): void
    {
        $subject = 'Reset Password DAYA Platform';
        $body = "Halo {$name},\n\nGunakan tautan berikut untuk mengatur ulang password Anda:\n{$resetUrl}\n\nJika Anda tidak meminta reset password, abaikan email ini.";
        $this->dispatchSafely($email, $subject, $body, 'password_reset');
    }

    private function dispatchSafely(string $email, string $subject, string $body, string $context): void
    {
        try {
            $this->dispatch($email, $subject, $body);
        } catch (Throwable $throwable) {
            error_log(
                'DAYA_AUTH_MAIL_FAILED'
                . ' CONTEXT=' . $context
                . ' TO=' . $email
                . ' SUBJECT=' . $subject
                . ' MESSAGE=' . $throwable->getMessage()
            );
        }
    }

    private function dispatch(string $email, string $subject, string $body): void
    {
        $this->ensureLogDirectory();
        $line = '[' . date('Y-m-d H:i:s') . '] TO=' . $email . ' | SUBJECT=' . $subject . PHP_EOL . $body . PHP_EOL . str_repeat('-', 80) . PHP_EOL;
        $this->writeLog($line);

        $mode = (string) config('authentication.mail_mode', 'log');

        if ($mode === 'mailketing') {
            $this->dispatchViaMailketing($email, $subject, $body);
            return;
        }

        if ($mode === 'mail') {
            $this->dispatchViaNativeMail($email, $subject, $body);
        }
    }

    private function dispatchViaNativeMail(string $email, string $subject, string $body): void
    {
        $headers = [
            'From: ' . (string) config('authentication.mail_from', 'no-reply@daya.local'),
            'Content-Type: text/plain; charset=UTF-8',
        ];

        $sent = mail($email, $subject, $body, implode("\r\n", $headers));
        if ($sent === false) {
            $failureLine = '[' . date('Y-m-d H:i:s') . '] MAIL_DELIVERY_FAILED TO=' . $email . ' | SUBJECT=' . $subject . PHP_EOL;
            $this->writeLog($failureLine);
        }
    }

    private function dispatchViaMailketing(string $email, string $subject, string $body): void
    {
        $config = $this->mailketingConfig();
        $payload = [
            'api_token' => $config['api_token'],
            'from_name' => $config['from_name'],
            'from_email' => $config['from_email'],
            'recipient' => $email,
            'subject' => $subject,
            'content' => nl2br(e($body)),
        ];

        $response = function_exists('curl_init')
            ? $this->postWithCurl($config['endpoint'], $payload, $config['timeout_seconds'])
            : $this->postWithStream($config['endpoint'], $payload, $config['timeout_seconds']);

        $decoded = json_decode($response['body'], true);
        if (!is_array($decoded)) {
            $this->writeLog('[' . date('Y-m-d H:i:s') . '] MAILKETING_FAILED TO=' . $email . ' | SUBJECT=' . $subject . ' | MESSAGE=Invalid JSON response' . PHP_EOL);
            throw new RuntimeException('Response Mailketing tidak valid.');
        }

        $status = strtolower((string) ($decoded['status'] ?? 'failed'));
        $message = (string) ($decoded['response'] ?? 'Unknown Mailketing response');
        if ($status !== 'success') {
            $this->writeLog('[' . date('Y-m-d H:i:s') . '] MAILKETING_FAILED TO=' . $email . ' | SUBJECT=' . $subject . ' | MESSAGE=' . $message . PHP_EOL);
            throw new RuntimeException('Mailketing gagal mengirim email: ' . $message);
        }

        $this->writeLog('[' . date('Y-m-d H:i:s') . '] MAILKETING_SENT TO=' . $email . ' | SUBJECT=' . $subject . ' | HTTP_STATUS=' . $response['status_code'] . PHP_EOL);
    }

    private function mailketingConfig(): array
    {
        $apiToken = trim((string) config('authentication.mailketing.api_token', ''));
        $fromName = trim((string) config('authentication.mailketing.from_name', config('app.name', 'DAYA Platform')));
        $fromEmail = trim((string) config('authentication.mailketing.from_email', config('authentication.mail_from', 'no-reply@daya.local')));
        $endpoint = trim((string) config('authentication.mailketing.endpoint', 'https://api.mailketing.co.id/api/v1/send'));
        $timeoutSeconds = max(5, (int) config('authentication.mailketing.timeout_seconds', 15));

        if ($apiToken === '') {
            throw new RuntimeException('AUTH_MAILKETING_API_TOKEN wajib diisi saat AUTH_MAIL_MODE=mailketing.');
        }

        if ($fromName === '') {
            throw new RuntimeException('AUTH_MAILKETING_FROM_NAME wajib diisi saat AUTH_MAIL_MODE=mailketing.');
        }

        if ($fromEmail === '') {
            throw new RuntimeException('AUTH_MAILKETING_FROM_EMAIL wajib diisi saat AUTH_MAIL_MODE=mailketing.');
        }

        if ($endpoint === '') {
            throw new RuntimeException('Endpoint Mailketing tidak valid.');
        }

        return [
            'api_token' => $apiToken,
            'from_name' => $fromName,
            'from_email' => $fromEmail,
            'endpoint' => $endpoint,
            'timeout_seconds' => $timeoutSeconds,
        ];
    }

    private function postWithCurl(string $endpoint, array $payload, int $timeoutSeconds): array
    {
        $handle = curl_init();
        if ($handle === false) {
            throw new RuntimeException('Gagal menginisialisasi CURL untuk Mailketing.');
        }

        curl_setopt_array($handle, [
            CURLOPT_URL => $endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeoutSeconds,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);

        $body = curl_exec($handle);
        if ($body === false) {
            $error = curl_error($handle);
            curl_close($handle);

            throw new RuntimeException('Request Mailketing gagal: ' . $error);
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        return [
            'body' => (string) $body,
            'status_code' => $statusCode,
        ];
    }

    private function postWithStream(string $endpoint, array $payload, int $timeoutSeconds): array
    {
        $errorMessage = null;
        set_error_handler(static function (int $severity, string $message) use (&$errorMessage): bool {
            $errorMessage = $message;
            return true;
        });

        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($payload),
                    'timeout' => $timeoutSeconds,
                    'ignore_errors' => true,
                ],
            ]);

            $body = file_get_contents($endpoint, false, $context);
        } finally {
            restore_error_handler();
        }

        if ($body === false) {
            throw new RuntimeException('Request Mailketing gagal: ' . ($errorMessage ?? 'Unknown stream error'));
        }

        $statusCode = 0;
        foreach ($http_response_header ?? [] as $headerLine) {
            if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $headerLine, $matches) === 1) {
                $statusCode = (int) $matches[1];
                break;
            }
        }

        return [
            'body' => (string) $body,
            'status_code' => $statusCode,
        ];
    }

    private function ensureLogDirectory(): void
    {
        $directory = storage_path('logs');
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Direktori log authentication tidak dapat dibuat.');
        }
    }

    private function writeLog(string $line): void
    {
        if (file_put_contents($this->logFilePath(), $line, FILE_APPEND | LOCK_EX) === false) {
            throw new RuntimeException('Gagal menulis log authentication mail.');
        }
    }

    private function logFilePath(): string
    {
        return storage_path('logs/auth-mail.log');
    }
}
