<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Domain\Models\ItemModel;
use App\Domain\Models\AdminModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;

class ItemController extends BaseController
{
    public function __construct(Container $container,private ItemModel $itemModel,private AdminModel $adminModel)
    {
        return parent::__construct($container);
    }
    //* get all items or filtered by category/search -> GET /items
    public function index(Request $request, Response $response, array $args): Response{
        $params = $request->getQueryParams();
        $categoryId = isset($params['category'])? (int)$params['category']:null;
        $search = trim($params['search']??'');
        // fetch items by search
        if($search!==''){
            $items = $this->itemModel->searchItems($search);
        }else if($categoryId){
            $items =$this->itemModel->getItemsByCategory($categoryId);
        }else{
            $items=$this->itemModel->getAllItems();
        }

        // fetch categories
        $categories =$this->adminModel->getAllCategories();
        $data=[
            'title'=>'Items for Sale',
            'items'=>$items,
            'categories'=>$categories,
            'categoryId'=>$categoryId,
            'search'=> $search
        ];

        return $this->render($response, 'items/item_list.php',$data);
    }

    // * show a single item detail -> GET /items/{id}
    public function show(Request $request, Response $response, array $args): Response {
        // code
        $id =(int)$args['id'];
        $item = $this->itemModel->getItemById($id);
        if(!$item){
            return $response->withHeader('Location', '/items')->withStatus(302);
        }
        $data=[
            'title'=> $item['listing_product'],
            'item'=>$item,
        ];

        return $this->render($response, 'items/item_detail.php',$data);
    }
}
