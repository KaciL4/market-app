<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Domain\Models\ItemModel;
use App\Domain\Models\AdminModel;
use App\Helpers\FlashMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use App\Helpers\SessionManager;

class ItemController extends BaseController
{
    public function __construct(Container $container, private ItemModel $itemModel, private AdminModel $adminModel)
    {
        return parent::__construct($container);
    }
    //* get all items or filtered by category/search -> GET /items
    public function index(Request $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();
        $categoryId = isset($params['category']) ? (int)$params['category'] : null;
        $search = trim($params['search'] ?? '');
        // Get current logged-in user ID (null if not logged in)
        $currentUserId = SessionManager::get('user_id');
        // fetch items by search
        if ($search !== '') {
            $items = $this->itemModel->searchItems($search, $currentUserId);
        } else if ($categoryId) {
            $items = $this->itemModel->getItemsByCategory($categoryId, $currentUserId);
        } else {
            $items = $this->itemModel->getAllItems($currentUserId);
        }

        // fetch categories
        $categories = $this->adminModel->getAllCategories();
        $data = [
            'title' => 'Items for Sale',
            'items' => $items,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'search' => $search
        ];

        return $this->render($response, 'items/item_list.php', $data);
    }

    // * show a single item detail -> GET /items/{id}
    public function show(Request $request, Response $response, array $args): Response
    {
        // code
        $id = (int)$args['id'];
        $item = $this->itemModel->getItemById($id);
        if (!$item) {
            return $response->withHeader('Location', '/items')->withStatus(302);
        }
        //get current logged-in user id for filtering recent items
        $currentUserId = SessionManager::get('user_id');

        // Fetch recent items (excluding current item if needed)
        $recentItems = $this->itemModel->getRecentItems($currentUserId);

        //filter out the current item from recent items to avoid duplication
        $recentItems = array_filter($recentItems, function ($recentItem) use ($id) {
            return $recentItem['item_id'] != $id;
        });
        $data = [
            'title' => $item['listing_product'],
            'item' => $item,
            'recentItems' => $recentItems,
        ];

        return $this->render($response, 'items/item_detail.php', $data);
    }

    public function searchApi(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();

        $search = trim($params['q'] ?? '');
        $categoryId = isset($params['category']) ? (int)$params['category'] : null;

        // Get current logged in user ID ->null if not logged in
        $currentUserId = SessionManager::get('user_id');

        // limit length
        if (strlen($search) > 100) {
            $search = substr($search, 0, 100);
        }

        $items = $this->itemModel->searchItemsApi($search, $categoryId, $currentUserId);
        $data = [
            'success' => true,
            'count' => count($items),
            'query' => $search,
            'category_id' => $categoryId,
            'items' => $items
        ];

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function myItemIndex(Request $request, Response $response, array $args): Response
    {
        // Get current logged-in user ID (null if not logged in)
        $currentUserId = SessionManager::get('user_id');

        $items = $this->itemModel->getItemsByUser($currentUserId);

        $data = [
            'title' => 'My Items',
            'items' => $items
        ];

        return $this->render($response, 'user/userItems.php', $data);
    }

    public function searchMyItemsApi(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $search = trim($params['q'] ?? '');

        $userId = SessionManager::get('user_id');

        $items = $this->itemModel->searchMyItems($userId, $search);

        $data = [
            'success' => true,
            'items' => $items
        ];

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function deleteItem(Request $request, Response $response, array $args): Response
    {
        $item_id = (int)$args['id'];

        $this->itemModel->deleteItem($item_id);

        FlashMessage::success('The selected item has been successfully deleted');

        return $this->redirect($request, $response, 'myItems.index');
    }
}
