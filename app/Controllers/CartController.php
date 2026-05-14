<?php

namespace App\Controllers;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\SessionManager;
use App\Domain\Models\ItemModel;
use App\Helpers\FlashMessage;


class CartController extends BaseController
{
    public function __construct(
        Container $container,
        private ItemModel $itemModel
        )
    {
        parent::__construct($container);
    }
    public function index(Request $request, Response $response, array $args): Response{
        $cart = SessionManager::get('cart', []);
        // TODO:Calculate the total number of items by summing the quantity of every entry in the cart.
        $itemCount = 0;

        // TODO: Calculate the grand total price by summing price * quantity for every entry.
        $totalPrice = 0;
        foreach ($cart as $item) {
            $itemCount += (int)($item['quantity'] ?? 1);
            $totalPrice += ($item['price']);
        }
        return $this->render($response, 'cart/cartIndexView.php',[
            "cart"=>$cart,
            'itemCount'=>$itemCount,
            'totalPrice'=>$totalPrice
        ]);
    }

    public function add(Request $request, Response $response, array $args): Response{
        $params = $request->getParsedBody();
        $itemId = (int)($params['item_id']??0);

        if($itemId <0){
            FlashMessage::error("Invalid Item Id (itemId <0)");
            return $this->redirect($request, $response, 'cart.index');
        }
        $item = $this->itemModel->findById($itemId);
        if(!$item){
            FlashMessage::error("The item does not exist.");
            return $this->redirect($request, $response, 'cart.index');
        }
        $cart=SessionManager::get('cart',[]);

        // if(isset($cart[$itemId])){
        //     $cart[$itemId]['quantity']++;
        // }

        $cart[$itemId] = [
            'item_id' => $item['item_id'],
            'name'    => $item['listing_product'],
            'price'   => (float)$item['price'],
            'image'   => $item['image_path'] ?? '',
            'quantity' => 1
         ];

        SessionManager::set('cart',$cart);
        FlashMessage::success("'{name}' was added to your cart.");
        return $response->withHeader('Location', APP_BASE_URL . "/items/$itemId?added=1")->withStatus(302);
    }

    public function update(Request $request, Response $response, array $args): Response{
        $params = $request->getParsedBody();
        $itemId = (int)($params['item_id']??0);
        $quantity = (int)($params['quantity']??0);
        $cart=SessionManager::get('cart',[]);
        if(!isset($cart[$itemId])){
            FlashMessage::error("The item is not in the cart");
            return $this->redirect($request,$response,'cart.index');
        }
        if($quantity<= 0){
            unset($cart[$itemId]);
            FlashMessage::info("Item remove successfully!");

        }
        else {
            $cart[$itemId]['quantity'] = $quantity;
            FlashMessage::success("Item quantity is updated.");
        }
        SessionManager::set('cart', $cart);
        return $this->redirect($request,$response,'cart.index');

    }
    public function remove(Request $request, Response $response, array $args): Response{
        $params = $request->getParsedBody();
        $itemId = (int)($params['item_id']??0);
        $cart =SessionManager::get('cart',[]);
        if(isset($cart[$itemId])){
            unset($cart[$itemId]);
            SessionManager::set('cart',$cart);
            FlashMessage::success("Item is removed successfully.");
        }else{
            FlashMessage::error("Could not find that item in your cart");
        }
        return $this->redirect($request,$response,'cart.index');

    }
    public function clear(Request $request, Response $response, array $args): Response{
        SessionManager::remove('cart');
        FlashMessage::success("Cart has been cleared.");

        return $this->redirect($request,$response,'cart.index');
    }
}
