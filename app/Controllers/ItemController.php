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
    public function __construct(Container $container)
    {
        return parent::__construct($container);
    }
    //TODO get all items or filtered by category/search -> GET /items
    public function index(Request $request, Response $response, array $args): Response{

        
        return $this->render($response, 'items/item_list.php',$data);
    }

    // TODO show a single item detail -> GET /items/{id}
    public function show(Request $request, Response $response, array $args): Response {
        // code

        return $this->render($response, 'items/item_detail.php',$data);
    }
}
