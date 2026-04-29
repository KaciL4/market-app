<?php

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;

class UserController extends BaseController
{
    public function __construct(Container $container) {
        parent::__construct($container);
    }

     public function dashboard(Request $resquest, Response $response, array $args): Response
    {
        $title = 'User Dashboard';
        $data = [
            'title' => $title,
            'username' => SessionManager::get('username', 'User')
        ];
        return $this->render($response, 'user/userDashboard.php', $data);
    }
}
