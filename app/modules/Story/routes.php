<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;
use App\Modules\Story\Controllers\AdminStoryController;
use App\Modules\Story\Controllers\StoryController;

$storyController = new StoryController();
$adminStoryController = new AdminStoryController();

$router->get('/story/admin', [$adminStoryController, 'index'], [AuthMiddleware::class, [PermissionMiddleware::class, 'story.admin.view']]);
$router->get('/story/admin/{id}', [$adminStoryController, 'show'], [AuthMiddleware::class, [PermissionMiddleware::class, 'story.admin.view']]);

$router->get('/story', [$storyController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/story/create', [$storyController, 'showCreate'], [AuthMiddleware::class]);
$router->post('/story/create', [$storyController, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/story/{id}', [$storyController, 'showOwn'], [AuthMiddleware::class]);
$router->get('/story/{id}/edit', [$storyController, 'showEdit'], [AuthMiddleware::class]);
$router->post('/story/{id}/edit', [$storyController, 'update'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/story/{id}/preview', [$storyController, 'preview'], [AuthMiddleware::class]);
$router->post('/story/{id}/review', [$storyController, 'review'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/story/{id}/publish', [$storyController, 'publish'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/story/{id}/schedule', [$storyController, 'schedule'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/story/{id}/archive', [$storyController, 'archive'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/story/{id}/duplicate', [$storyController, 'duplicate'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/story/{id}/delete', [$storyController, 'destroy'], [AuthMiddleware::class, CsrfMiddleware::class]);

$router->get('/stories', [$storyController, 'publicList']);
$router->get('/stories/{slug}', [$storyController, 'publicShow']);
