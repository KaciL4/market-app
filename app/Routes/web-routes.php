<?php

declare(strict_types=1);

/**
 * This file contains the routes for the web application.
 */

use App\Helpers\SessionManager;
use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\UploadController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\ItemController;
use App\Controllers\TwoFactorController;
use App\Middleware\AdminAuthMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\TwoFactorMiddleware;
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
    })
        ->add(TwoFactorMiddleware::class)
        ->add(AdminAuthMiddleware::class)
        ->add(AuthMiddleware::class);

    // Route for to show Auth
    $app->group('/auth', function ($group) {
        // User Login (GET)
        $group->get('/login', [AuthController::class, 'login'])
            ->setName('auth.login');

        $group->post('/login', [AuthController::class, 'authenticate'])
            ->setName('auth.authenticate');

        $group->get('/logout', [AuthController::class, 'logout'])
            ->setName('auth.logout');

        // User Register Submit (GET)
        $group->get('/register', [AuthController::class, 'register'])
            ->setName('auth.register');

        // User Input Store (POST)
        $group->post('/register', [AuthController::class, 'store'])
            ->setName('auth.store');

        // $group->post('/logout', [AuthController::class, 'logout'])
        //     ->setName('auth.logout.post'); // If use POST method in 2fa-verify.php
    });

    // Items routes
    $app->get('/items', [ItemController::class, 'index'])->setName('items.index');

    $app->get('/items/{id}', [ItemController::class, 'show'])->setName('items.show');

    // Live Item Search
    $app->get('/api/items/search', [ItemController::class, 'searchApi'])
        ->setName('api.items.search');

    // User Dashboard
    $app->get('/dashboard', [UserController::class, 'dashboard'])
        ->setName('user.dashboard')
        ->add(TwoFactorMiddleware::class)
        ->add(AuthMiddleware::class);

    // My Items
    $app->get('/my-items', [ItemController::class, 'myItemIndex'])
        ->setName('myItems.index');

    // Live My Item Search
    $app->get('/api/my-items/search', [ItemController::class, 'searchMyItemsApi'])
        ->setName('api.myItems.search');

    $app->get('/items/{id}/delete', [ItemController::class, 'deleteItem'])
        ->setName('items.delete');

    // profile page
    $app->group('/profile', function ($group) {
        $group->get('', [UserController::class, 'profile'])->setName('profile.index');
        $group->post('/update', [UserController::class, 'updateProfile'])->setName('profile.update');
    })->add(AuthMiddleware::class);


    //* Cart
    $app->group('/cart', function ($group) {
        $group->get('', [CartController::class, 'index'])->setName('cart.index');

        $group->post('/add', [CartController::class, 'add'])->setName('cart.add');

        $group->post('/update', [CartController::class, 'update'])->setName('cart.update');

        $group->post('/remove', [CartController::class, 'remove'])->setName('cart.remove');

        $group->post('/clear', [CartController::class, 'clear'])->setName('cart.clear');
        $group->get('/checkout', [CartController::class, 'checkout'])->setName('cart.checkout');

        $group->post('/process', [CartController::class, 'process'])->setName('cart.process');

        $group->get('/bill/{transaction_id}', [CartController::class, 'receipt'])->setName('cart.receipt');
    });

    $app->group('/2fa', function ($group) {
        $group->get('/setup', [TwoFactorController::class, 'showSetup'])
            ->setName('2fa.setup')
            ->add(AuthMiddleware::class);

        $group->post('/verify-and-enable', [TwoFactorController::class, 'verifyAndEnable'])
            ->setName('2fa.enable')
            ->add(AuthMiddleware::class);

        $group->get('/verify', [TwoFactorController::class, 'showVerify'])
            ->setName('2fa.verify')
            ->add(AuthMiddleware::class);

        $group->post('/verify', [TwoFactorController::class, 'verify'])
            ->setName('2fa.verify.post')
            ->add(AuthMiddleware::class);

        $group->get('/disable', [TwoFactorController::class, 'showDisable'])
            ->setName('2fa.disable.show')
            ->add(TwoFactorMiddleware::class)
            ->add(AuthMiddleware::class);

        $group->post('/disable', [TwoFactorController::class, 'disable'])
            ->setName('2fa.disable')
            ->add(TwoFactorMiddleware::class)
            ->add(AuthMiddleware::class);
    });
};
