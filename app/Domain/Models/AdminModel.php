<?php

namespace App\Domain\Models;
use App\Helpers\Core\PDOService;
use PDO;
class AdminModel extends BaseModel
{
    public function __construct(PDOService $db_service)
    {
        parent::__construct($db_service);
    }
    public function getTotalUsers(): int{
        $sql = "SELECT COUNT(*) FROM users";
        return $this->count($sql);
    }
    public function getTotalCategories(): int{
       $sql= "SELECT COUNT(*)FROM category";
        return $this->count($sql);
    }
    public function getTotalItems(): int{
       $sql= "SELECT COUNT(*)FROM items";
        return $this->count($sql);
    }
    public function getTotalTransactions(): int{
       $sql= "SELECT COUNT(*)FROM transactions";
        return $this->count($sql);
    }
    public function getAllUsers(): array{
        $stmt = $this->pdo->query(
            'SELECT user_id, username, email, role, created_at FROM users'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM users WHERE user_id = :id'
        );
        return $stmt->execute(['id' => $id]);
    }

}
