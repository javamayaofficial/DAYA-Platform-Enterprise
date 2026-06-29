<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Logging\Logger;

final class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly Logger $logger
    ) {
    }

    public function handle(Request $request): Response
    {
        return $this->router->dispatch($request);
    }

    public function logger(): Logger
    {
        return $this->logger;
    }

    public function router(): Router
    {
        return $this->router;
    }
}
