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
        $data=[
            'title'=>'Admin Dashboard',
            'totalUsers'=>$totalUsers,
            'totalCategories'=>$totalCategories,
            'totalItems'=>$totalItems,
            'totalTransactions'=>$totalTransactions,
            'username'=>$_SESSION['username']??'Admin'
        ];
        return $this->render($response,'admin/adminDashboard.php', $data);
    }
}
