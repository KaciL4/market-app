<?php

namespace App\Domain\Models;

use PDO;

class ItemModel extends BaseModel
{
    public function getAllItems(?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.status = 'Available'";

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

    public function getItemsByCategory(int $categoryId, ?int $currentUserId = null)
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.status = 'Available' AND i.category_id = :category_id";

        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC";

        $params = ['category_id' => $categoryId];

        if ($currentUserId !== null) {
            $params['user_id'] = $currentUserId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getItemById(int $id): array|false
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.item_id = :id LIMIT 1";

        return $this->selectOne($sql, ['id' => $id]);
    }

    public function searchItems(string $search, ?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.status = 'Available' AND i.listing_product LIKE :search";

        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC";

        $params = ['search' => '%' . $search . '%'];

        if ($currentUserId !== null) {
            $params['user_id'] = $currentUserId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchItemsApi(string $searchTerm = '', ?int $categoryId = null, ?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
        FROM items i
        JOIN category c ON i.category_id = c.category_id
        JOIN users u ON i.user_id = u.user_id
        LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
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

        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
            $params['user_id'] = $currentUserId;
        }

        $sql .= " ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, $params);
    }

    public function getAllItemsForAdmin(): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, ia.status AS review_status, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_approval ia ON i.item_id = ia.item_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                ORDER BY i.listing_date DESC";

        return $this->selectAll($sql);
    }

    public function updateReviewStatus(int $itemId, string $status): int
    {
        $this->pdo->beginTransaction();

        try {
            $sql = "UPDATE item_approval
                    SET status = :status
                    WHERE item_id = :item_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'status' => $status,
                'item_id' => $itemId
            ]);

            $itemStatus = $status === 'Approved' ? 'Available' : 'Pending';

            $sql2 = "UPDATE items
                     SET status = :status
                     WHERE item_id = :item_id";

            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute([
                'status' => $itemStatus,
                'item_id' => $itemId
            ]);

            $this->pdo->commit();

            return 1;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return 0;
        }
    }

    public function searchItemsForAdmin(string $search): array
    {
        $search = '%' . $search . '%';

        $sql = "SELECT i.*, c.category_name, u.username, ia.status AS review_status, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_approval ia ON i.item_id = ia.item_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.listing_product LIKE ?
                   OR i.detail LIKE ?
                   OR u.username LIKE ?
                ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, [$search, $search, $search]);
    }

    public function getRecentItems(?int $currentUserId = null): array
    {
        $sql = "SELECT i.*, img.file_path
                FROM items i
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.status = 'Available'";

        if ($currentUserId !== null) {
            $sql .= " AND i.user_id != :user_id";
        }

        $sql .= " ORDER BY i.listing_date DESC LIMIT 3";

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
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.item_id = :id LIMIT 1";

        return $this->selectOne($sql, ['id' => $id]);
    }
    /**
     * Fetch all categories for the product form dropdown.
     */
    public function getAllCategories(): array
    {
        return $this->selectAll("SELECT category_id, category_name FROM category ORDER BY category_name ASC");
    }
    public function markAsSold(int $itemId): bool
    {
        $sql = "UPDATE items SET status = 'Sold' WHERE item_id = :id";
        return $this->execute($sql, ['id' => $itemId]) > 0;
    }

    public function getItemsByUser(int $userId): array|false
    {
        $sql = "SELECT i.*, c.category_name, u.username, u.email, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.user_id = :user_id
                ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, ['user_id' => $userId]);
    }

    public function searchMyItems(int $userId, string $searchTerm = ''): array
    {
        $sql = "SELECT i.*, c.category_name, u.username, img.file_path
                FROM items i
                JOIN category c ON i.category_id = c.category_id
                JOIN users u ON i.user_id = u.user_id
                LEFT JOIN item_images img ON i.item_id = img.item_id AND img.is_primary = 1
                WHERE i.user_id = :user_id";

        $params = ['user_id' => $userId];

        if (!empty($searchTerm)) {
            $sql .= " AND i.listing_product LIKE CONCAT('%', :search, '%')";
            $params['search'] = $searchTerm;
        }

        $sql .= " ORDER BY i.listing_date DESC";

        return $this->selectAll($sql, $params);
    }
    //* User update item
     public function update(int $item_id, array $data): int
    {
        return $this->execute(
            "UPDATE items
            SET category_id = :category_id,
                listing_product = :listing_product,
                price = :price,
                detail = :detail
            WHERE item_id = :item_id",
            [
                'item_id' => $item_id,
                'category_id' => $data['category_id'],
                'listing_product' => $data['listing_product'],
                'price' => $data['price'],
                'detail' => $data['detail']
            ]
        );
    }
    //* User delete item
    public function deleteItem(int $itemId): bool
    {
        $result = $this->execute(
            "DELETE FROM items WHERE item_id = :item_id",
            ['item_id' => $itemId]
        );

        return $result > 0;
    }
    //* Count user items
    public function countItemsByUser(int $userId): int
    {
        return $this->count(
            "SELECT COUNT(*) FROM items WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
    }


    public function createPendingItem(array $data): int
    {
        $sql = "INSERT INTO items
                (user_id, category_id, listing_product, price, detail, listing_date, status)
                VALUES
                (:user_id, :category_id, :listing_product, :price, :detail, CURDATE(), 'Pending')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'],
            'listing_product' => $data['listing_product'],
            'price' => $data['price'],
            'detail' => $data['detail']
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function addItemApproval(int $itemId): bool
    {
        $sql = "INSERT INTO item_approval (item_id, status, date)
                VALUES (:item_id, 'Pending', CURDATE())";

        return $this->execute($sql, ['item_id' => $itemId]) > 0;
    }

    public function addItemImage(int $itemId, string $filePath, int $isPrimary = 1): bool
    {
        $sql = "INSERT INTO item_images (item_id, file_path, is_primary)
                VALUES (:item_id, :file_path, :is_primary)";

        return $this->execute($sql, [
            'item_id' => $itemId,
            'file_path' => $filePath,
            'is_primary' => $isPrimary
        ]) > 0;
    }
}
