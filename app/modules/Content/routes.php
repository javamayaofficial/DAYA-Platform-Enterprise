<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;
use App\Modules\Content\Controllers\AdminContentController;
use App\Modules\Content\Controllers\ContentController;

$contentController = new ContentController();
$adminContentController = new AdminContentController();

$router->get('/content', [$contentController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/content/create', [$contentController, 'showCreate'], [AuthMiddleware::class]);
$router->post('/content/create', [$contentController, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/content/{id}', [$contentController, 'showOwn'], [AuthMiddleware::class]);
$router->get('/content/{id}/edit', [$contentController, 'showEdit'], [AuthMiddleware::class]);
$router->post('/content/{id}/edit', [$contentController, 'update'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/content/{id}/parts', [$contentController, 'addPart'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/content/{id}/parts/{partId}/delete', [$contentController, 'deletePart'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/content/{id}/submit', [$contentController, 'submit'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/content/{id}/delete', [$contentController, 'destroy'], [AuthMiddleware::class, CsrfMiddleware::class]);

$router->get('/contents', [$contentController, 'publicList']);
$router->get('/contents/{slug}', [$contentController, 'publicShow']);

$router->get('/content/admin', [$adminContentController, 'index'], [AuthMiddleware::class, [PermissionMiddleware::class, 'content.admin.view']]);
$router->get('/content/admin/{id}', [$adminContentController, 'show'], [AuthMiddleware::class, [PermissionMiddleware::class, 'content.admin.view']]);
$router->post('/content/admin/{id}/review', [$adminContentController, 'review'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'content.admin.review']]);
