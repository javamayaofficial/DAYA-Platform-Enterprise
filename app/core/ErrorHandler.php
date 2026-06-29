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
            if ($error === null) {
                return;
            }

            $logger->error($error['message'], $error);
            Response::html(self::renderHtml(500, $debug ? $error['message'] : 'Fatal error terdeteksi.', null), 500)->send();
        });
    }

    private static function renderHtml(int $statusCode, string $message, ?Throwable $throwable): string
    {
        $detail = $throwable instanceof Throwable
            ? '<pre class="small bg-dark text-white rounded p-3 overflow-auto">' . htmlspecialchars((string) $throwable, ENT_QUOTES, 'UTF-8') . '</pre>'
            : '';

        return '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Error</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><div class="container py-5"><div class="card shadow-sm border-0"><div class="card-body p-4"><span class="badge text-bg-danger mb-3">HTTP ' . $statusCode . '</span><h1 class="h4 mb-3">Aplikasi berhenti dengan aman.</h1><p class="text-secondary">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>' . $detail . '</div></div></div></body></html>';
    }
}
