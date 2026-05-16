<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use PDO;
use PDOException;

class ItemModel extends BaseModel
{
    // *get all available items with category name
    public function getAllItems(?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available'";

        // Exclude current user's own items if logged in
        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->prepare($sql);

        if ($currentUserId !== null) {
            $stmt->execute(['user_id' => $currentUserId]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //* get items by category
    public function getItemsByCategory(int $categoryId,?int $currentUserId = null)
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available' AND i.category_id = :category_id";

        // Exclude current user's own items if logged in
        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->prepare($sql);

        $params = ['category_id' => $categoryId];
        if ($currentUserId !== null) {
            $params['user_id'] = $currentUserId;
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //TODO get item by ID
    public function getItemById(int $id): array|false {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.item_id = :id LIMIT 1";
        return $this->selectOne($sql, ['id' => $id]);
    }

    //*search items by name
    public function searchItems(string $search,?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.status = 'Available' AND i.listing_product LIKE :search";

        // Exclude current user's own items if logged in
        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->prepare($sql);

        $params = ['search' => '%' . $search . '%'];
        if ($currentUserId !== null) {
            $params['user_id'] = $currentUserId;
        }

        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchItemsApi(string $searchTerm = '', ?int $categoryId = null,?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username
            FROM items i
            JOIN category c ON i.category_id = c.category_id
            JOIN users u ON i.user_id = u.user_id
            WHERE i.status = 'Available'";

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
        // Exclude current user's own items if logged in
        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
            $params['user_id'] = $currentUserId;
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

    public function getRecentItems(?int $currentUserId = null): array
    {
        $sql = "SELECT * FROM items WHERE status = 'Available'";
        // Exclude current user's own items if logged in
        if ($currentUserId !== null) {
            $sql .= " AND user_id != :user_id";
        }
        $sql .= " ORDER BY listing_date DESC LIMIT 3";
        $stmt = $this->pdo->prepare($sql);
        if ($currentUserId !== null) {
            $stmt->execute(['user_id' => $currentUserId]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById(int $id): array|false
    {
        // TODO: Execute a SELECT query to fetch a single product by ID
        //       - Use $this->selectOne() with a WHERE clause
        $sql = "SELECT i.*, c.category_name, u.username
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                WHERE i.item_id = :id LIMIT 1";
        return $this->selectOne($sql, ['id' => $id]);
    }
    public function markAsSold(int $itemId): bool
    {
        $sql = "UPDATE items SET status = 'Sold' WHERE item_id = :id";
        return $this->execute($sql, ['id' => $itemId]) > 0;
    }

    //TODO get image for an item
}
