<?php

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;

class UserController extends BaseController
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function dashboard(Request $resquest, Response $response, array $args): Response
    {
        $title = 'User Dashboard';
        $data = [
            'title' => $title,
            'username' => SessionManager::get('username', 'User')
        ];

        // TODO: Our user dashboard is in UserController rather than AuthController as indicated in Lab-13
        // 1. Query the database to check whether the current user has
        //    2FA enabled.
        // 2. Render 'dashboard.php', passing the 2FA status so the
        //    view can display the correct toggle button.

        return $this->render($response, 'user/userDashboard.php', $data);
    }
}
