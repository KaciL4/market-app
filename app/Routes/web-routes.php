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

    // Route for to show Admin Dashboard
    $app->group('/admin', function ($group) {
        // Admin Dashboard route
        $group->get('/dashboard', [AdminController::class, 'dashboard'])
            ->setName('admin.dashboard');
    });

    // Route for to show Admin Dashboard + Admin Auth
    $app->group('/admin', function ($group) {

    // Admin Login (GET)
    $group->get('/login', [AdminController::class, 'showLogin'])
        ->setName('admin.showLogin');

    // Admin Login Submit (POST)
    $group->post('/login', [AdminController::class, 'login'])
        ->setName('admin.login');
});

    // User Login (GET)
    $app->get('/login', [AuthController::class, 'showLogin'])
        ->setName('auth.showLogin');

    // User Login Submit (POST)
    $app->post('/login', [AuthController::class, 'login'])
        ->setName('auth.login');

    // User Register (GET)
    $app->get('/register', [AuthController::class, 'showRegister'])
        ->setName('auth.showRegister');

    // User Register Submit (POST)
    $app->post('/register', [AuthController::class, 'register'])
        ->setName('auth.register');
};
