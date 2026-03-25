<?php

namespace App\Controllers;
use App\Domain\Models\AdminModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class AdminController extends BaseController
{
    public function __construct(
        Container $container,
        private AdminModel $dashboardModel
    ) {
        parent::__construct($container);
    }
    public function dashboard(Request $resquest, Response $response, array $args): Response{
        $totalUsers = $this->dashboardModel->getTotalUsers();
        $totalCategories = $this->dashboardModel->getTotalCategories();
        $totalItems = $this->dashboardModel->getTotalItems();
        $totalTransactions = $this->dashboardModel->getTotalTransactions();
        $title = 'Admin Dashboard';
        $data=[
            'title'=>$title,
            'totalUsers'=>$totalUsers,
            'totalCategories'=>$totalCategories,
            'totalItems'=>$totalItems,
            'totalTransactions'=>$totalTransactions,
            'username'=>$_SESSION['username']??'Admin'
        ];
        return $this->render($response,'admin/adminDashboard.php', $data);
    }
    public function showLogin(Request $request, Response $response): Response{
        ob_start();
        require __DIR__ . '/../Views/admin/adminLogin.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
    public function login(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        if($username === 'admin' && $password === 'admin123'){
            session_start();
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = 'Admin';
            $_SESSION['role'] = 'admin';
            return $response
                ->withHeader('Location', '/admin/dashboard')
                ->withStatus(302);
        }
        return $response
            ->withHeader('Location', '/admin/login')
            ->withStatus(302);
    }
    // GET /admin/user_management
    public function userManagement(Request $request, Response $response): Response{
        $users = $this->dashboardModel->getAllUsers();
        $data=[
            'title'=>'User Management',
            'users'=>$users,
            'username'=>$_SESSION['username']??'Admin'
        ];
        return $this->render($response,'admin/userManagement.php', $data);
    }
    // POST /admin/user_management/delete/{id}
    public function deleteUser(Request $request, Response $response, array $args): Response{
        $userId = (int)$args['id'];
        $this->dashboardModel->deleteUser($userId);
        return $response
            ->withHeader('Location', '/admin/user_management')
            ->withStatus(302);
    }
}
