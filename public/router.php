<?php

declare(strict_types=1);

$documentRoot = __DIR__;
$requestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$resolvedPath = realpath($documentRoot . DIRECTORY_SEPARATOR . ltrim($requestPath, '/'));

if ($requestPath === '/install' || $requestPath === '/install/') {
    require $documentRoot . '/install/index.php';

    return true;
}

if (
    $resolvedPath !== false
    && str_starts_with($resolvedPath, realpath($documentRoot) ?: $documentRoot)
    && is_file($resolvedPath)
) {
    return false;
}

require $documentRoot . '/index.php';
