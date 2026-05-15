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
            $totalPrice += ($item['price']* ($item['quantity'] ?? 1));
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

        $params = $request->getParsedBody();
        // Get user from session
        $userId = SessionManager::get('user_id');
        $user = SessionManager::get('user');
        // If user_id not found in session, try to get from user array
        if (!$userId && $user && isset($user['user_id'])) {
            $userId = $user['user_id'];
        }
        $cart = SessionManager::get('cart', []);

        // Validate user is logged in
        if (!$userId) {
            FlashMessage::error("You must be logged in to place an order.");
            return $this->redirect($request, $response, 'auth.login');
        }
        // Validate cart is not empty
        if (empty($cart)) {
            FlashMessage::error("Your cart is empty.");
            return $this->redirect($request, $response, 'cart.index');
        }
        // Get shipping address and payment method from form
        $shippingAddress = trim($params['address'] ?? '');
        $paymentMethod = trim($params['paymentMethod'] ?? 'credit');
        // Validate shipping address
        if (empty($shippingAddress)) {
            FlashMessage::error("Please provide a shipping address.");
            return $this->redirect($request, $response, 'cart.checkout');
        }
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        $taxAmount = $subtotal * 0.15;
        $totalPrice = $subtotal + $taxAmount;

        try {
            // Start transaction using public TransactionModel method
            $this->transactionModel->beginTransaction();

            $lastId = null;
            $successCount = 0;

            foreach ($cart as $itemId => $details) {
                // Create transaction record for each item
                $transactionId = $this->transactionModel->createTransaction(
                    (int)$userId,
                    (int)$itemId,
                    $details['price'] * $details['quantity'],
                    $shippingAddress,
                    $paymentMethod
                );

                if ($transactionId) {
                    $lastId = $transactionId;
                    $successCount++;

                    // Mark item as sold
                    $this->itemModel->markAsSold((int)$itemId);
                } else {
                    throw new \Exception("Failed to create transaction for item ID: $itemId");
                }
            }
            $this->transactionModel->commit();
            // Clear the cart
            SessionManager::remove('cart');

            FlashMessage::success("Purchase completed successfully! $successCount item(s) purchased.");

            // Redirect to receipt page with the last transaction ID
            if ($lastId) {
                return $this->redirect($request, $response, 'cart.receipt', ['transaction_id' => $lastId]);
            } else {
                return $this->redirect($request, $response, 'cart.checkout');
            }

        } catch (\Exception $e) {
            $this->transactionModel->rollback();
            error_log("Checkout failed: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
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
