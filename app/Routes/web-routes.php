<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Helpers\SessionManager;
use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\UploadController;
use App\Controllers\AuthController;
use App\Controllers\ItemController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return static function (Slim\App $app): void {


    //* NOTE: Route naming pattern: [controller_name].[method_name]
    $app->get('/', [HomeController::class, 'index'])
        ->setName('home.index');

    $app->get('/home', [HomeController::class, 'index'])
        ->setName('home.index');

    // A route to display PHP configuration information.
    $app->get('/phpinfo', function (Request $request, Response $response, $args) {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();
        $response->getBody()->write($phpinfo);
        return $response;
    });

    // A route to test runtime error handling and custom exceptions.
    $app->get('/error', function (Request $request, Response $response, $args) {
        throw new \Slim\Exception\HttpBadRequestException($request, "This is a runtime error. Something went wrong");
    });

    // Route for to show Admin Pages
    $app->group('/admin', function ($group) {
        // Admin Dashboard route
        $group->get('/dashboard', [AdminController::class, 'dashboard'])
            ->setName('admin.dashboard');
        $group->get('/user_management', [AdminController::class, 'userManagement'])
            ->setName('admin.userManagement');

        $group->post('/user_management/delete/{id}', [AdminController::class, 'deleteUser'])
            ->setName('admin.deleteUser');
        $group->get('/categories', [AdminController::class, 'categories'])
            ->setName('admin.categories');

        $group->post('/categories/add', [AdminController::class, 'addCategory'])
            ->setName('admin.addCategory');

        $group->post('/categories/edit/{id}', [AdminController::class, 'editCategory'])
            ->setName('admin.editCategory');

        $group->post('/categories/delete/{id}', [AdminController::class, 'deleteCategory'])
            ->setName('admin.deleteCategory');

        $group->get('/item_management', [AdminController::class, 'itemManagement'])
            ->setName('admin.itemManagement');

        $group->post('/item_management', [AdminController::class, 'itemManagement'])
            ->setName('admin.itemManagement.post');

        $group->get('/profile', [AdminController::class, 'profile'])
            ->setName('admin.profile');

        $group->get('/transactions', [AdminController::class, 'transactions'])
            ->setName('admin.transactions');

        $group->get('/upload', [UploadController::class, 'index'])
            ->setName('upload.index');

        $group->post('/upload', [UploadController::class, 'upload'])
            ->setName('upload.process');

        $group->post('/upload/delete', [UploadController::class, 'delete'])
            ->setName('upload.delete');
    });

    // Route for to show Auth
    $app->group('/auth', function ($group) {
        // User Login (GET)
        $group->get('/login', [AuthController::class, 'showLogin'])
            ->setName('auth.showLogin');

        // User Login Submit (POST)
        $group->post('/login', [AuthController::class, 'login'])
            ->setName('auth.login');

        // User Register Submit (GET)
        $group->get('/register', [AuthController::class, 'register'])
            ->setName('auth.register');

        // User Input Store (POST)
        $group->post('/register', [AuthController::class, 'store'])
            ->setName('auth.store');
    });

    // Items routes
    $app->get('/items', [ItemController::class, 'index'])->setName('items.index');

    $app->get('/items/{id}', [ItemController::class, 'show'])->setName('items.show');

    $app->get('/api/items/search', [ItemController::class, 'searchApi'])
        ->setName('api.items.search');
};
