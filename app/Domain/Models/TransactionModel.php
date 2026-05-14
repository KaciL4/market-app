<?php

namespace App\Domain\Models;

use PDO;

class TransactionModel extends BaseModel
{
    //*Records a new purchase in the database.
    public function createTransaction(int $userId, int $itemId, float $totalPrice): int|false
    {
        // Changed 'total_price' to 'total' to match your DB screenshot
        $sql = "INSERT INTO transactions (user_id, item_id,  transaction_date)
                VALUES (:user_id, :item_id, :transaction_date)";

        $this->execute($sql, [
            'user_id' => $userId,
            'item_id' => $itemId,
            // 'total'   => $totalPrice, //* this maps to the :total placeholder
            'transaction_date' => date('Y-m-d H:i:s')
        ]);

        return (int)$this->pdo->lastInsertId();
    }
    //*Fetches details for the bill/receipt page.
    public function getTransactionDetails(int $transactionId): array|false
    {
        // Changed t.total_price to t.total
        $sql = "SELECT t.*, i.listing_product, i.price as original_price
                FROM transactions t
                JOIN items i ON t.item_id = i.item_id
                WHERE t.transaction_id = :id";

        return $this->selectOne($sql, ['id' => $transactionId]);
    }
}
