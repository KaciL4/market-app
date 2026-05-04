<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use PDO;
use PDOException;

class ItemModel extends BaseModel
{
    // *get all available items with category name
    public function getAllItems(): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available'
                ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get items by category
    public function getItemsByCategory(int $categoryId)
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available' AND i.category_id = :category_id
                ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //TODO get item by ID
    public function getItemById(int $id): array|false
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.item_id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //*search items by name
    public function searchItems(string $search): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available' AND i.listing_product LIKE :search
                ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['search' => '%' . $search . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchItemsApi(string $searchTerm = '', ?int $categoryId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
            FROM items i
            JOIN category c ON i.category_id = c.category_id
            JOIN users u ON i.user_id = u.user_id"; // for available status WHERE i.status = 'Available'

        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND (i.listing_product LIKE CONCAT('%', :search1, '%') OR i.detail LIKE CONCAT('%', :search2, '%'))";

            $params['search1'] = $searchTerm;
            $params['search2'] = $searchTerm;
        }

        if (!empty($categoryId)) {
            $sql .= " AND i.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, $params);
    }

    //* get all items for admin panel
    public function getAllItemsForAdmin(): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, ia.status AS review_status
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_approval ia ON i.item_id = ia.item_id
                ORDER BY i.listing_date DESC";

        return $this->selectAll($sql);
    }

    //* update review status
    public function updateReviewStatus(int $itemId, string $status): int
    {
        $sql = "UPDATE item_approval
                SET status = :status
                WHERE item_id = :item_id";

        return $this->execute($sql, [
            'status' => $status,
            'item_id' => $itemId
        ]);
    }

    public function searchItemsForAdmin(string $search): array
    {
        $search = '%' . $search . '%';

        $sql = "SELECT i.*, c.category_name, u.username, ia.status AS review_status
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_approval ia ON i.item_id = ia.item_id
                WHERE i.listing_product LIKE ?
                   OR i.detail LIKE ?
                   OR u.username LIKE ?
                ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, [$search, $search, $search]);
    }

    public function getRecentItems(): array
    {
        $sql = "SELECT * FROM items ORDER BY listing_date DESC LIMIT 3";
        return $this->selectAll($sql);
    }
    public function findById(int $id): array|false
    {
        // TODO: Execute a SELECT query to fetch a single product by ID
        //       - Use $this->selectOne() with a WHERE clause
        $item = $this->selectOne('SELECT * FROM items WHERE id = :id LIMIT 1', ['id' => $id]);
        //       - Return the product as an associative array, or false if not found
        return $item ? $item : false;
    }

    //TODO get image for an item
}
