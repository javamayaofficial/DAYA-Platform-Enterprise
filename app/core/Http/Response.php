<?php

declare(strict_types=1);

namespace App\Core\Http;

final class Response
{
    public function __construct(
        private readonly string $body = '',
        private readonly int $statusCode = 200,
        private readonly array $headers = []
    ) {
    }

    public static function html(string $body, int $statusCode = 200, array $headers = []): self
    {
        return new self($body, $statusCode, array_merge(['Content-Type' => 'text/html; charset=UTF-8'], $headers));
    }

    public static function json(array $payload, int $statusCode = 200, array $headers = []): self
    {
        return new self((string) json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), $statusCode, array_merge(['Content-Type' => 'application/json; charset=UTF-8'], $headers));
    }

    public static function redirect(string $location, int $statusCode = 302, array $headers = []): self
    {
        $normalizedLocation = \app_url($location);

        return new self('', $statusCode, array_merge(['Location' => $normalizedLocation], $headers));
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->mergedHeaders() as $name => $value) {
            header($name . ': ' . $value, true);
        }

        echo $this->body;
    }

    private function mergedHeaders(): array
    {
        return array_merge($this->defaultSecurityHeaders(), $this->headers);
    }

    private function defaultSecurityHeaders(): array
    {
        if (!(bool) \config('app.security.headers_enabled', true)) {
            return [];
        }

        $headers = [
            'X-Frame-Options' => (string) \config('app.security.frame_options', 'SAMEORIGIN'),
            'X-Content-Type-Options' => (string) \config('app.security.content_type_options', 'nosniff'),
            'Referrer-Policy' => (string) \config('app.security.referrer_policy', 'strict-origin-when-cross-origin'),
            'Permissions-Policy' => (string) \config('app.security.permissions_policy', 'camera=(), microphone=(), geolocation=()'),
            'X-Permitted-Cross-Domain-Policies' => (string) \config('app.security.cross_domain_policies', 'none'),
        ];

        if ((bool) \config('app.security.hsts_enabled', false)) {
            $headers['Strict-Transport-Security'] = 'max-age=' . max(0, (int) \config('app.security.hsts_max_age', 31536000)) . '; includeSubDomains';
        }

        return $headers;
    }
}
