<?php

namespace App\Domain\Models;
use App\Helpers\Core\PDOService;
use PDO;
use PDOException;
class ItemModel extends BaseModel
{
    // *get all available items with category name
    public function getAllItems():array {
        // code
        $sql = "SELECT i.* c.category_name FROM items i JOIN category c ON i.category_id = c.category_id WHERE i status = 'Available' ORDER BY i.listing_date DESC";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    //* get items by category
        public function getItemsByCategory(int $categoryId) {
            // code
            $sql ="SELECT i.* ,c.category_name FROM items i JOIN category c ON i.category_id = c.category_id WHERE i.status ='Available' AND i.category_id = :category_id ORDER BY i.listing_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['category_id'=> $categoryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    //TODO get item by ID

    //TODO search items by name

    //TODO get image for an item
}
