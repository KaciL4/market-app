<?php

declare(strict_types=1);

use App\Middleware\SessionMiddleware;
use App\Middleware\LocaleMiddleware;
use App\Middleware\ExceptionMiddleware;
use Slim\App;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    // Slim runs middleware in reverse order.
    // So Session must be added AFTER Locale to run BEFORE Locale.
    $app->add(LocaleMiddleware::class);
    $app->add(SessionMiddleware::class);

    // Error/exception middleware last.
    $app->add(ExceptionMiddleware::class);
};
