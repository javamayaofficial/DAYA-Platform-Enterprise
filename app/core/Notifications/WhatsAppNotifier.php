<?php

declare(strict_types=1);

namespace App\Core\Notifications;

use RuntimeException;

final class WhatsAppNotifier
{
    public function send(string $target, string $message, array $options = []): array
    {
        $mode = (string) config('app.whatsapp.mode', 'off');
        $trimmedTarget = trim($target);
        $trimmedMessage = trim($message);
        $countryCode = (string) ($options['countryCode'] ?? config('app.whatsapp.default_country_code', '62'));

        if ($trimmedTarget === '') {
            throw new RuntimeException('Target WhatsApp wajib diisi.');
        }

        if ($trimmedMessage === '') {
            throw new RuntimeException('Pesan WhatsApp wajib diisi.');
        }

        $normalizedTarget = $this->normalizeTarget($trimmedTarget, $countryCode);
        $this->ensureLogDirectory();

        if ($mode === 'off') {
            $this->writeLog('[' . date('Y-m-d H:i:s') . '] WHATSAPP_SKIPPED MODE=off TARGET=' . $normalizedTarget . PHP_EOL);

            return [
                'status' => 'skipped',
                'response' => 'WhatsApp notification is disabled.',
                'target' => $normalizedTarget,
            ];
        }

        if ($mode === 'log') {
            $this->writeLog(
                '[' . date('Y-m-d H:i:s') . '] WHATSAPP_LOGGED TARGET=' . $normalizedTarget . ' | MESSAGE=' . $trimmedMessage . PHP_EOL
            );

            return [
                'status' => 'logged',
                'response' => 'WhatsApp notification logged only.',
                'target' => $normalizedTarget,
            ];
        }

        if ($mode !== 'fonnte') {
            throw new RuntimeException('Mode WhatsApp tidak dikenal: ' . $mode);
        }

        return $this->sendViaFonnte($normalizedTarget, $trimmedMessage, $options);
    }

    private function sendViaFonnte(string $target, string $message, array $options): array
    {
        $config = $this->fonnteConfig();
        $payload = [
            'target' => $target,
            'message' => $message,
            'countryCode' => (string) ($options['countryCode'] ?? $config['country_code']),
            'typing' => $this->normalizeBooleanOption($options['typing'] ?? $config['typing']),
            'connectOnly' => $this->normalizeBooleanOption($options['connectOnly'] ?? $config['connect_only']),
        ];

        if (isset($options['schedule']) && $options['schedule'] !== null && $options['schedule'] !== '') {
            $payload['schedule'] = (int) $options['schedule'];
        }

        if (isset($options['delay']) && $options['delay'] !== null && $options['delay'] !== '') {
            $payload['delay'] = (string) $options['delay'];
        }

        if (isset($options['url']) && trim((string) $options['url']) !== '') {
            $payload['url'] = trim((string) $options['url']);
        }

        if (isset($options['filename']) && trim((string) $options['filename']) !== '') {
            $payload['filename'] = trim((string) $options['filename']);
        }

        $response = function_exists('curl_init')
            ? $this->postWithCurl($config['endpoint'], $config['token'], $payload, $config['timeout_seconds'])
            : $this->postWithStream($config['endpoint'], $config['token'], $payload, $config['timeout_seconds']);

        $decoded = json_decode($response['body'], true);
        if (!is_array($decoded)) {
            $this->writeLog('[' . date('Y-m-d H:i:s') . '] FONNTE_FAILED TARGET=' . $target . ' | MESSAGE=Invalid JSON response' . PHP_EOL);
            throw new RuntimeException('Response Fonnte tidak valid.');
        }

        $status = $this->isSuccessfulStatus($decoded['status'] ?? false);
        $detail = $this->extractFonnteResponseMessage($decoded);
        if ($status !== true) {
            $this->writeLog('[' . date('Y-m-d H:i:s') . '] FONNTE_FAILED TARGET=' . $target . ' | MESSAGE=' . $detail . PHP_EOL);
            throw new RuntimeException('Fonnte gagal mengirim pesan: ' . $detail);
        }

        $this->writeLog('[' . date('Y-m-d H:i:s') . '] FONNTE_SENT TARGET=' . $target . ' | HTTP_STATUS=' . $response['status_code'] . PHP_EOL);

        return [
            'status' => 'sent',
            'response' => $detail,
            'http_status' => $response['status_code'],
            'raw' => $decoded,
        ];
    }

    private function fonnteConfig(): array
    {
        $token = trim((string) config('app.whatsapp.fonnte.token', ''));
        $endpoint = trim((string) config('app.whatsapp.fonnte.endpoint', 'https://api.fonnte.com/send'));
        $countryCode = trim((string) config('app.whatsapp.default_country_code', '62'));
        $timeoutSeconds = max(5, (int) config('app.whatsapp.fonnte.timeout_seconds', 15));

        if ($token === '') {
            throw new RuntimeException('WHATSAPP_FONNTE_TOKEN wajib diisi saat WHATSAPP_MODE=fonnte.');
        }

        if ($endpoint === '') {
            throw new RuntimeException('Endpoint Fonnte tidak valid.');
        }

        return [
            'token' => $token,
            'endpoint' => $endpoint,
            'country_code' => $countryCode,
            'timeout_seconds' => $timeoutSeconds,
            'typing' => (bool) config('app.whatsapp.fonnte.typing', false),
            'connect_only' => (bool) config('app.whatsapp.fonnte.connect_only', true),
        ];
    }

    private function postWithCurl(string $endpoint, string $token, array $payload, int $timeoutSeconds): array
    {
        $handle = curl_init();
        if ($handle === false) {
            throw new RuntimeException('Gagal menginisialisasi CURL untuk Fonnte.');
        }

        curl_setopt_array($handle, [
            CURLOPT_URL => $endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeoutSeconds,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $body = curl_exec($handle);
        if ($body === false) {
            $error = curl_error($handle);
            curl_close($handle);

            throw new RuntimeException('Request Fonnte gagal: ' . $error);
        }

        $statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        curl_close($handle);

        return [
            'body' => (string) $body,
            'status_code' => $statusCode,
        ];
    }

    private function postWithStream(string $endpoint, string $token, array $payload, int $timeoutSeconds): array
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
                    'header' => "Authorization: {$token}\r\nContent-Type: application/x-www-form-urlencoded\r\n",
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
            throw new RuntimeException('Request Fonnte gagal: ' . ($errorMessage ?? 'Unknown stream error'));
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

    private function extractFonnteResponseMessage(array $response): string
    {
        $detail = $response['detail'] ?? $response['reason'] ?? $response['message'] ?? $response['process'] ?? null;

        if (is_string($detail) && trim($detail) !== '') {
            return trim($detail);
        }

        return 'OK';
    }

    private function isSuccessfulStatus(mixed $status): bool
    {
        if (is_bool($status)) {
            return $status;
        }

        return in_array(strtolower(trim((string) $status)), ['1', 'true', 'success'], true);
    }

    private function normalizeBooleanOption(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'yes', 'on'], true) ? 'true' : 'false';
    }

    private function normalizeTarget(string $target, string $countryCode): string
    {
        $sanitized = preg_replace('/\D+/', '', $target);
        if (!is_string($sanitized) || $sanitized === '') {
            throw new RuntimeException('Target WhatsApp tidak valid.');
        }

        $normalizedCountryCode = $this->normalizeCountryCode($countryCode);

        if (str_starts_with($sanitized, '00')) {
            $sanitized = substr($sanitized, 2);
        }

        if ($normalizedCountryCode !== '0') {
            if (str_starts_with($sanitized, '0')) {
                $sanitized = $normalizedCountryCode . substr($sanitized, 1);
            } elseif (!str_starts_with($sanitized, $normalizedCountryCode)) {
                $sanitized = $normalizedCountryCode . ltrim($sanitized, '0');
            }
        }

        $length = strlen($sanitized);
        if ($length < 10 || $length > 20) {
            throw new RuntimeException('Panjang target WhatsApp tidak valid.');
        }

        return $sanitized;
    }

    private function normalizeCountryCode(string $countryCode): string
    {
        $normalized = preg_replace('/\D+/', '', trim($countryCode));
        if (!is_string($normalized) || $normalized === '') {
            return '62';
        }

        return $normalized;
    }

    private function ensureLogDirectory(): void
    {
        $directory = storage_path('logs');
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Direktori log WhatsApp tidak dapat dibuat.');
        }
    }

    private function writeLog(string $line): void
    {
        if (file_put_contents($this->logFilePath(), $line, FILE_APPEND | LOCK_EX) === false) {
            throw new RuntimeException('Gagal menulis log WhatsApp.');
        }
    }

    private function logFilePath(): string
    {
        return storage_path('logs/whatsapp.log');
    }
}
