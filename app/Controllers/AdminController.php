<?php

namespace App\Controllers;

use App\Domain\Models\AdminModel;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use App\Domain\Models\ItemModel;

class AdminController extends BaseController
{
    public function __construct(
        Container $container,
        private AdminModel $dashboardModel,
        private ItemModel $itemModel
    ) {
        parent::__construct($container);
    }
    public function dashboard(Request $resquest, Response $response, array $args): Response
    {
        $totalUsers = $this->dashboardModel->getTotalUsers();
        $totalCategories = $this->dashboardModel->getTotalCategories();
        $totalItems = $this->dashboardModel->getTotalItems();
        $totalTransactions = $this->dashboardModel->getTotalTransactions();
        $title = 'Admin Dashboard';
        $data = [
            'title' => $title,
            'totalUsers' => $totalUsers,
            'totalCategories' => $totalCategories,
            'totalItems' => $totalItems,
            'totalTransactions' => $totalTransactions,
            'username' => SessionManager::get('username', 'Admin')
        ];

        // TODO: Maybe for Admin Dashboard
        // 1. Query the database to check whether the current user has
        //    2FA enabled.
        // 2. Render 'dashboard.php', passing the 2FA status so the
        //    view can display the correct toggle button.

        return $this->render($response, 'admin/adminDashboard.php', $data);
    }
    public function showLogin(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/admin/login.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->dashboardModel->findByUsername($username);
        // check user exist and role = admin
        if (!$user || $data['role'] !== 'admin') { // If cause any problem change $data with $user
            return $response
                ->withHeader('Location', '/admin/login?error=invalid')
                ->withStatus(302);
        }
        // check password
        if ($password !== $data['password']) {
            return $response
                ->withHeader('Location', '/admin/login?error=invalid')
                ->withStatus(302);
        }
        // session_start();
        // $_SESSION['user_id'] = $user['user_id'];
        // $_SESSION['username'] = $user['username'];
        // $_SESSION['role'] = $user['role'];

        SessionManager::set('user_id', $data['user_id']);
        SessionManager::set('username', $data['username']);
        SessionManager::set('role', $data['role']);
        return $response
            ->withHeader('Location', '/admin/dashboard')
            ->withStatus(302);

        return $response
            ->withHeader('Location', '/admin/login')
            ->withStatus(302);
    }
    // GET /admin/user_management
    public function userManagement(Request $request, Response $response): Response
    {
        $search = $request->getQueryParams()['search'] ?? '';
        $users = $this->dashboardModel->getAllUsers($search);
        $data = [
            'title' => 'User Management',
            'users' => $users,
            'username' => SessionManager::get('username', 'Admin')
        ];
        return $this->render($response, 'admin/userManagement.php', $data);
    }
    // POST /admin/user_management/delete/{id}
    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $userId = (int)$args['id'];
        $deleted = $this->dashboardModel->deleteUser($userId);

        if ($deleted) {
            FlashMessage::success("User deleted successfully");
        } else {
            FlashMessage::error("Failed to delete as user owns item(s)");
        }

        return $response
            ->withHeader('Location', APP_BASE_URL . '/admin/user_management')
            ->withStatus(302);
    }
    // GET /admin/categories
    public function categories(Request $request, Response $response): Response
    {

        $categories = $this->dashboardModel->getAllCategories();
        $data = [
            'title' => 'Categories',
            'categories' => $categories,
            'username' => SessionManager::get('username', 'Admin')
        ];
        return $this->render($response, 'admin/categories.php', $data);
    }
    // POST /admin/categories/add
    public function addCategory(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $categoryName = $data['category_name'] ?? '';
        if ($categoryName) {
            $this->dashboardModel->addCategory($categoryName);
        }
        return $response
            ->withHeader('Location', '/admin/categories')
            ->withStatus(302);
    }
    // POST /admin/categories/edit/{id}
    public function editCategory(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int)$args['id'];
        $data = $request->getParsedBody();
        $categoryName = $data['category_name'] ?? '';
        if ($categoryName) {
            $this->dashboardModel->editCategory($categoryId, $categoryName);
        }
        return $response
            ->withHeader('Location', '/admin/categories?success=edited')
            ->withStatus(302);
    }
    // POST /admin/categories/delete/{id}
    public function deleteCategory(Request $request, Response $response, array $args): Response
    {
        $categoryId = (int)$args['id'];
        $this->dashboardModel->deleteCategory($categoryId);
        return $response
            ->withHeader('Location', '/admin/categories?success=deleted')
            ->withStatus(302);
    }

    //This function retrieves search and status parameters, filters items accordingly, and renders the item management page with the data.
    public function itemManagement(Request $request, Response $response): Response
    {
        if ($request->getMethod() === 'POST') {
            $data = $request->getParsedBody();

            $itemId = $data['item_id'] ?? null;
            $action = $data['action'] ?? '';

            if ($itemId && $action === 'approve') {
                $this->itemModel->updateReviewStatus((int)$itemId, 'Approved');
            }

            if ($itemId && $action === 'reject') {
                $this->itemModel->updateReviewStatus((int)$itemId, 'Rejected');
            }

            return $response
                ->withHeader('Location', APP_BASE_URL . '/admin/item_management?lang=' . SessionManager::get('locale', 'en'))
                ->withStatus(302);
        }

        $search = trim($request->getQueryParams()['search'] ?? '');

        if ($search !== '') {
            $items = $this->itemModel->searchItemsForAdmin($search);
        } else {
            $items = $this->itemModel->getAllItemsForAdmin();
        }

        $data = [
            'title' => 'Item Management',
            'items' => $items,
            'search' => $search
        ];

        return $this->render($response, 'admin/itemManagement.php', $data);
    }

    public function profile(Request $request, Response $response): Response
    {
        $profile = $this->dashboardModel->getAdminProfile(SessionManager::get('user_id'), ['role' => 'admin']);

        $data = [
            'title' => 'Admin Profile',
            'username' => SessionManager::get('username', 'Admin'),
            'profile' => $profile
        ];
        return $this->render($response, 'profile/profileView.php', $data);
    }

    public function transactions(Request $request, Response $response): Response
    {
        $transactions = $this->dashboardModel->getAllTransactions();

        $data = [
            'title' => 'Transactions',
            'transactions' => $transactions
        ];
        return $this->render($response, 'admin/transactions.php', $data);
    }
}
