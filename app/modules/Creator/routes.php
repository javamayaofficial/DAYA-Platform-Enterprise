<?php

declare(strict_types=1);

use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Modules\Authentication\Middleware\PermissionMiddleware;
use App\Modules\Creator\Controllers\AdminCreatorController;
use App\Modules\Creator\Controllers\CreatorController;

$creatorController = new CreatorController();
$adminCreatorController = new AdminCreatorController();

$router->get('/creator', [$creatorController, 'dashboard'], [AuthMiddleware::class]);
$router->get('/creator/register', [$creatorController, 'showCreate'], [AuthMiddleware::class]);
$router->post('/creator/register', [$creatorController, 'store'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get('/creator/profile', [$creatorController, 'showProfile'], [AuthMiddleware::class]);
$router->get('/creator/profile/edit', [$creatorController, 'showEdit'], [AuthMiddleware::class]);
$router->post('/creator/profile/edit', [$creatorController, 'update'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/settings', [$creatorController, 'updateSettings'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/delete', [$creatorController, 'destroy'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/social-links', [$creatorController, 'addSocialLink'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/social-links/{id}/delete', [$creatorController, 'deleteSocialLink'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/portfolio', [$creatorController, 'addPortfolioItem'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/portfolio/{id}/delete', [$creatorController, 'deletePortfolioItem'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/achievements', [$creatorController, 'addAchievement'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->post('/creator/profile/achievements/{id}/delete', [$creatorController, 'deleteAchievement'], [AuthMiddleware::class, CsrfMiddleware::class]);

$router->get('/creators', [$creatorController, 'publicList']);
$router->get('/creators/{handle}', [$creatorController, 'publicShow']);

$router->get('/creator/admin', [$adminCreatorController, 'index'], [AuthMiddleware::class, [PermissionMiddleware::class, 'creator.admin.view']]);
$router->get('/creator/admin/{id}', [$adminCreatorController, 'show'], [AuthMiddleware::class, [PermissionMiddleware::class, 'creator.admin.view']]);
$router->post('/creator/admin/{id}/review', [$adminCreatorController, 'review'], [AuthMiddleware::class, CsrfMiddleware::class, [PermissionMiddleware::class, 'creator.admin.review']]);
