<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;
use App\Modules\Collection\Controllers\AdminCollectionController;
use App\Modules\Collection\Controllers\CollectionController;

$collectionController = new CollectionController();
$adminCollectionController = new AdminCollectionController();

$router->get('/collection/admin', [$adminCollectionController, 'index'], [AuthMiddleware::class, [PermissionMiddleware::class, 'collection.admin.view']]);
$router->get('/collection/admin/{id}', [$adminCollectionController, 'show'], [AuthMiddleware::class, [PermissionMiddleware::class, 'collection.admin.view']]);

$router->get('/collection', [$collectionController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/collection/create', [$collectionController, 'showCreate'], [AuthMiddleware::class]);
$router->post('/collection/create', [$collectionController, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/collection/{id}', [$collectionController, 'showOwn'], [AuthMiddleware::class]);
$router->get('/collection/{id}/edit', [$collectionController, 'showEdit'], [AuthMiddleware::class]);
$router->post('/collection/{id}/edit', [$collectionController, 'update'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/items', [$collectionController, 'addItem'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/items/reorder', [$collectionController, 'reorderItems'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/items/{itemId}/delete', [$collectionController, 'deleteItem'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/publish', [$collectionController, 'publish'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/draft', [$collectionController, 'draft'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/collection/{id}/delete', [$collectionController, 'destroy'], [AuthMiddleware::class, CsrfMiddleware::class]);

$router->get('/collections', [$collectionController, 'publicList']);
$router->get('/collections/{slug}', [$collectionController, 'publicShow']);
