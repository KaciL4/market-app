<?php

namespace App\Domain\Models;

use PDO;

class TransactionModel extends BaseModel
{
    // called function from the BaseModel
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
     // called function from the BaseModel
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
     // called function from the BaseModel
    public function rollback(): bool
    {
        return $this->pdo->rollback();
    }
    //*Records a new purchase in the database.
    public function createTransaction(int $userId, int $itemId, float $totalPrice = 0, string $shippingAddress = '', string $paymentMethod = ''): int|false
    {
        $sql = "INSERT INTO transactions (user_id, item_id, item_purchased, transaction_date, total_price, status, shipping_address, payment_method)
                VALUES (:user_id, :item_id, :item_purchased, :transaction_date, :total_price, :status, :shipping_address, :payment_method)";

        $result = $this->execute($sql, [
            'user_id'          => $userId,
            'item_id'          => $itemId,
            'item_purchased'   => 1,
            'transaction_date' => date('Y-m-d H:i:s'),
            'total_price'      => $totalPrice,
            'status'           => 'completed',
            'shipping_address' => $shippingAddress,
            'payment_method'   => $paymentMethod
        ]);

        if ($result > 0) {
            return (int)$this->lastInsertId();
        }

        return false;
    }

    //*Fetches details for the bill/receipt page.
    public function getTransactionDetails(int $transactionId): array|false
    {
        $sql = "SELECT t.*, i.listing_product, i.price, i.detail, u.username, u.email
                FROM transactions t
                JOIN items i ON t.item_id = i.item_id
                JOIN users u ON t.user_id = u.user_id
                WHERE t.transaction_id = :id";

        $result = $this->selectOne($sql, ['id' => $transactionId]);

        if ($result) {
            // Calculate total with tax if not already stored
            if (!isset($result['total_paid'])) {
                $result['total_paid'] = round($result['price'] * 1.15, 2);
            }
            if (!isset($result['original_price'])) {
                $result['original_price'] = $result['price'];
            }
        }

        return $result;
    }
}
