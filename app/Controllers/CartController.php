<?php

namespace App\Controllers;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\SessionManager;
use App\Domain\Models\ItemModel;
use App\Helpers\FlashMessage;
use App\Domain\Models\TransactionModel;


class CartController extends BaseController
{
    public function __construct(
        Container $container,
        private ItemModel $itemModel,
        private TransactionModel $transactionModel
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
    public function checkout(Request $request, Response $response, array $args): Response
    {
        $cart = SessionManager::get('cart', []);
        if (empty($cart)) {
            FlashMessage::info("Your cart is empty.");
            return $this->redirect($request, $response, 'cart.index');
        }

        $user = SessionManager::get('user');
        $isLoggedIn = !empty($user);

        //Calculate Subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        //Calculate tax (15%)
        $taxRate = 0.15;
        $taxAmount = $subtotal * $taxRate;

        //Calculate final total
        $totalPrice = $subtotal + $taxAmount;

        return $this->render($response, 'cart/checkoutView.php', [
            'title' => 'Checkout',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'taxAmount' => $taxAmount,
            'totalPrice' => $totalPrice,
            'user' => $user,
            'isLoggedIn' => $isLoggedIn
        ]);
    }
    // *process the transaction of the checkout
    public function process(Request $request, Response $response, array $args): Response
    {
        // get the user ID from the session
        $userId = SessionManager::get('user_id');
        $cart = SessionManager::get('cart', []);

        if (!$userId || empty($cart)) {
            FlashMessage::error("You must be logged in to place an order.");
            return $this->redirect($request, $response, 'cart.checkout');
        }

        try {
            $lastId = 0;
            foreach ($cart as $itemId => $details) {
                // 3. Use the $userId variable here instead of $user['user_id']
                $lastId = $this->transactionModel->createTransaction(
                    (int)$userId,
                    (int)$itemId,
                    (float)($details['price'] * 1.15) // Adding tax
                );

                $this->itemModel->markAsSold((int)$itemId);
            }

            SessionManager::remove('cart');
            FlashMessage::success("Purchase completed!");

            // 4. Ensure this route name matches your web-routes.php (cart.receipt)
            return $this->redirect($request, $response, 'cart.receipt', ['transaction_id' => $lastId]);

        } catch (\Exception $e) {
            FlashMessage::error("Checkout failed: " . $e->getMessage());
            return $this->redirect($request, $response, 'cart.checkout');
        }
    }
    // * function for the purchase receipt
    public function receipt(Request $request, Response $response, array $args): Response
    {
        $transactionId = (int)$args['transaction_id'];
        $transaction = $this->transactionModel->getTransactionDetails($transactionId);

        if (!$transaction) {
            return $this->redirect($request, $response, 'home.index');
        }

        return $this->render($response, './cart/transactionBillView.php', [
            'title' => 'Your Receipt',
            'transaction' => $transaction
        ]);
    }
}
