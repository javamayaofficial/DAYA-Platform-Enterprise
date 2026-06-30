<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\HttpException;
use App\Core\Http\Response;
use App\Core\Logging\Logger;
use Throwable;

final class ErrorHandler
{
    public static function register(Logger $logger, bool $debug = false): void
    {
        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if ((error_reporting() & $severity) === 0) {
                return false;
            }

            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(static function (Throwable $throwable) use ($logger, $debug): void {
            $statusCode = $throwable instanceof HttpException ? $throwable->statusCode() : 500;
            $logger->error($throwable->getMessage(), [
                'type' => $throwable::class,
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ]);

            $message = $statusCode === 500 && !$debug
                ? 'Terjadi kesalahan pada aplikasi.'
                : $throwable->getMessage();

            Response::html(self::renderHtml($statusCode, $message, $debug ? $throwable : null), $statusCode)->send();
        });

        register_shutdown_function(static function () use ($logger, $debug): void {
            $error = error_get_last();
            if ($error === null || !self::isFatalShutdownError((int) ($error['type'] ?? 0))) {
                return;
            }

            $logger->error($error['message'], $error);

            if (!headers_sent()) {
                Response::html(self::renderHtml(500, $debug ? $error['message'] : 'Fatal error terdeteksi.', null), 500)->send();
            }
        });
    }

    private static function renderHtml(int $statusCode, string $message, ?Throwable $throwable): string
    {
        $detail = $throwable instanceof Throwable
            ? '<pre class="small bg-dark text-white rounded p-3 overflow-auto">' . htmlspecialchars((string) $throwable, ENT_QUOTES, 'UTF-8') . '</pre>'
            : '';

        $content = '<div class="card shadow-sm border-0"><div class="card-body p-4">'
            . '<span class="badge text-bg-danger mb-3">HTTP ' . $statusCode . '</span>'
            . '<h1 class="h4 mb-3">Aplikasi berhenti dengan aman.</h1>'
            . '<p class="text-secondary">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
            . $detail
            . '</div></div>';

        return render_layout('Error', $content);
    }

    private static function isFatalShutdownError(int $type): bool
    {
        return in_array($type, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true);
    }
}
