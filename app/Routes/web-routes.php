<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
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
    });

    // Route for to show Auth
    $app->group('/auth', function ($group) {
    // User Login (GET)
        $group->get('/login', [AuthController::class, 'showLogin'])
            ->setName('auth.showLogin');

        // User Login Submit (POST)
        $group->post('/login', [AuthController::class, 'login'])
            ->setName('auth.login');

        // User Register (GET)
        $group->get('/register', [AuthController::class, 'showRegister'])
            ->setName('auth.showRegister');

        // User Register Submit (POST)
        $group->post('/register', [AuthController::class, 'register'])
            ->setName('auth.register');

});


};
