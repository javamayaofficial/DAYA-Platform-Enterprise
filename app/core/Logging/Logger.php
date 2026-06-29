<?php

declare(strict_types=1);

namespace App\Core\Logging;

use DateTimeImmutable;

final class Logger
{
    public function __construct(
        private readonly string $logDirectory,
        private readonly string $minimumLevel = 'debug'
    ) {
        if (!is_dir($this->logDirectory)) {
            mkdir($this->logDirectory, 0775, true);
        }
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log('debug', $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $timestamp = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $contextJson = $context === [] ? '{}' : json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $line = sprintf("[%s] %s: %s %s%s", $timestamp, strtoupper($level), $message, $contextJson, PHP_EOL);
        $filename = $this->logDirectory . DIRECTORY_SEPARATOR . 'app-' . date('Y-m-d') . '.log';

        file_put_contents($filename, $line, FILE_APPEND | LOCK_EX);
    }
}
