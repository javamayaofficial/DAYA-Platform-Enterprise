<?php

declare(strict_types=1);

use App\Core\Application;
use App\Core\Autoloader;
use App\Core\Config;
use App\Core\Env;
use App\Core\ErrorHandler;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Http\SessionManager;
use App\Core\Logging\Logger;
use App\Core\ModuleManager;
use App\Core\Router;

if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', dirname(__DIR__, 2));
}

require_once APP_BASE_PATH . '/app/helpers/functions.php';
require_once APP_BASE_PATH . '/app/core/Autoloader.php';

$autoloader = new Autoloader();
$autoloader->addNamespace('App\\Core\\', APP_BASE_PATH . '/app/core');
$autoloader->addNamespace('App\\Middleware\\', APP_BASE_PATH . '/app/middleware');
$autoloader->addNamespace('App\\Modules\\', APP_BASE_PATH . '/app/modules');
$autoloader->register();

Env::load(storage_path('config/.env'));
Config::load([
    'app' => require config_path('app.php'),
    'database' => require config_path('database.php'),
]);
$moduleManager = new ModuleManager(APP_BASE_PATH . '/app/modules');
$moduleManager->loadConfigurations();

date_default_timezone_set((string) config('app.timezone', 'UTC'));

$logger = new Logger(storage_path('logs'), (string) config('app.log_level', 'debug'));
ErrorHandler::register($logger, (bool) config('app.debug', false));

$session = new SessionManager((array) config('app.session', []));
$session->start();

$router = new Router();
$app = new Application($router, $logger);

require config_path('routes.php');

$request = Request::capture();
$request->setSession($session);

$moduleManager->bootModules($request, $logger);

$requestPath = $request->path();
$isInstallerRequest = $requestPath === '/install' || str_starts_with($requestPath, '/install/');
$isInstallerAssetRequest = str_starts_with($requestPath, '/assets/');

if (
    !config('app.installed', false)
    && !$isInstallerRequest
    && !$isInstallerAssetRequest
    && !in_array($requestPath, ['/health', '/favicon.ico'], true)
) {
    Response::redirect('/install')->send();

    return;
}

$app->handle($request)->send();
