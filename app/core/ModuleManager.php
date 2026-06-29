<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Http\Request;
use App\Core\Logging\Logger;
use App\Core\Modular\BaseModule;

final class ModuleManager
{
    public function __construct(private readonly string $modulesPath)
    {
    }

    public function loadConfigurations(): void
    {
        foreach ($this->moduleDirectories() as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);
            $moduleKey = strtolower($moduleName);
            $configFiles = glob($moduleDirectory . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . '*.php') ?: [];
            sort($configFiles);

            foreach ($configFiles as $configFile) {
                $configName = pathinfo($configFile, PATHINFO_FILENAME);
                $configData = require $configFile;

                if ($configName === 'module') {
                    Config::set($moduleKey, $configData);
                    Config::set('modules.' . $moduleKey . '.module', $configData);

                    continue;
                }

                Config::set('modules.' . $moduleKey . '.' . $configName, $configData);
            }
        }
    }

    public function registerRoutes(Router $router): void
    {
        foreach ($this->moduleDirectories() as $moduleDirectory) {
            $routesFile = $moduleDirectory . DIRECTORY_SEPARATOR . 'routes.php';
            if (!is_file($routesFile)) {
                continue;
            }

            require $routesFile;
        }
    }

    public function bootModules(Request $request, Logger $logger): void
    {
        foreach ($this->moduleDirectories() as $moduleDirectory) {
            $moduleName = basename($moduleDirectory);
            $moduleClass = 'App\\Modules\\' . $moduleName . '\\' . $moduleName . 'Module';
            if (class_exists($moduleClass) && method_exists($moduleClass, 'instance')) {
                $module = $moduleClass::instance();
                if ($module instanceof BaseModule) {
                    $module->boot($request, $logger);

                    continue;
                }
            }

            $bootstrapClass = 'App\\Modules\\' . $moduleName . '\\Services\\ModuleBootstrap';
            if (class_exists($bootstrapClass) && method_exists($bootstrapClass, 'boot')) {
                $bootstrapClass::boot($request, $logger);
            }
        }
    }

    private function moduleDirectories(): array
    {
        $directories = glob($this->modulesPath . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR) ?: [];
        sort($directories);

        return $directories;
    }
}
