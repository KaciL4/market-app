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
use App\Helpers\FileUploadHelper;


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
    public function showUploadForm(Request $request, Response $response, array $args): Response
    {
        $categories = $this->adminModel->getAllCategories();

        return $this->render($response, 'items/upload_item.php', [
            'title' => trans('items.upload_new_item'),
            'categories' => $categories
        ]);
    }

    public function storeUploadedItem(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();

        $userId = SessionManager::get('user_id');

        if (!$userId) {
            FlashMessage::error(trans('flash.fill_all_fields'));
            return $this->redirect($request, $response, 'auth.login');
        }

        $categoryId = (int)($data['category_id'] ?? 0);
        $listingProduct = trim($data['listing_product'] ?? '');
        $price = (float)($data['price'] ?? 0);
        $detail = trim($data['detail'] ?? '');

        if ($categoryId <= 0 || empty($listingProduct) || $price <= 0 || empty($detail)) {
            FlashMessage::error(trans('flash.fill_all_fields'));
            return $this->redirect($request, $response, 'items.upload');
        }

        $itemId = $this->itemModel->createPendingItem([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'listing_product' => $listingProduct,
            'price' => $price,
            'detail' => $detail
        ]);

        if (!$itemId) {
            FlashMessage::error(trans('flash.account_create_failed'));
            return $this->redirect($request, $response, 'items.upload');
        }

        $this->itemModel->addItemApproval($itemId);

        if (isset($uploadedFiles['item_image'])) {
            $uploadResult = FileUploadHelper::upload($uploadedFiles['item_image'], [
                'directory' => dirname(__DIR__, 2) . '/public/uploads/images',
                'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                'maxSize' => 5 * 1024 * 1024,
                'filenamePrefix' => 'item_'
            ]);

            if ($uploadResult->isSuccess()) {
                $filename = $uploadResult->getData()['filename'];
                $filePath = '/uploads/images/' . $filename;
                $this->itemModel->addItemImage($itemId, $filePath, 1);
            }
        }

        FlashMessage::success(trans('flash.item_submitted'));
        return $this->redirect($request, $response, 'myItems.index');
    }

    public function editItem(Request $request, Response $response, array $args): Response
    {
        $item_id = (int)$args['id'];

        $item = $this->itemModel->findById($item_id);

        if (!$item) {
            FlashMessage::error('Item not found!');
            $this->redirect($request, $response, 'myItems.index');
        }

        $categories = $this->itemModel->getAllCategories();

        $data = [
            'title' => 'Edit Item',
            'item' => $item,
            'categories' => $categories
        ];

        return $this->render($response, 'user/userItemEditView.php', $data);
    }

    public function updateItem(Request $request, Response $response, array $args): Response
    {
        $item_id = $args['id'];

        $data = $request->getParsedBody();

        $category_id = $data['category_id'];
        $listing_product = $data['listing_product'];
        $price = $data['price'];
        $detail = $data['detail'];

        if (empty($category_id)) {
            FlashMessage::error('Please select a category');
            return $this->redirect($request, $response, 'products.edit');
        }

        if (empty($listing_product)) {
            FlashMessage::error('Please put a valid product name');
            return $this->redirect($request, $response, 'items.edit');
        }

        if (empty($price)) {
            FlashMessage::error('Please put a valid price');
            return $this->redirect($request, $response, 'items.edit');
        }

        if (empty($detail)) {
            FlashMessage::error('Please put a description');
            return $this->redirect($request, $response, 'items.edit');
        }

        $this->itemModel->update($item_id, $data);

        FlashMessage::success('Product has been updated successfully');

        return $this->redirect($request, $response, 'myItems.index');
    }

    public function deleteItem(Request $request, Response $response, array $args): Response
    {
        $item_id = (int)$args['id'];

        $this->itemModel->deleteItem($item_id);

        FlashMessage::success('The selected item has been successfully deleted');

        return $this->redirect($request, $response, 'myItems.index');
    }
}
