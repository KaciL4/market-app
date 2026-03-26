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
    // find user by username for admin login
    public function findByUsername(string $username){
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username AND role =:role LIMIT 1");
        $stmt->execute([
            'username'=>$username,
            'role'=> 'admin'
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?:null;
    }
    public function getAllUsers(string $search =''): array{
        if($search !==''){
            $stmt = $this->pdo->prepare(
                'SELECT user_id, username, email, role, created_at FROM users WHERE username LIKE :search'
            );
            $stmt->execute(['search' => "%$search%"]);
        }else{
            $stmt = $this->pdo->query(
                'SELECT user_id, username, email, role, created_at FROM users'
            );
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM users WHERE user_id = :id'
        );
        return $stmt->execute(['id' => $id]);
    }
    public function getAllCategories(): array{
        $stmt = $this->pdo->query(
            'SELECT * FROM category'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
     }
     public function addCategory(string $name): bool{
        $stmt = $this->pdo->prepare(
            'INSERT INTO category (category_name) VALUES (:name)'
        );
        return $stmt->execute(['name' => $name]);
     }
     public function editCategory(int $id, string $name): bool{
        $stmt = $this->pdo->prepare(
            'UPDATE category SET category_name = :name WHERE category_id = :id'
        );
        return $stmt->execute(['id' => $id, 'name' => $name]);
     }
     public function deleteCategory(int $id): bool{
        $stmt = $this->pdo->prepare(
            'DELETE FROM category WHERE category_id = :id');
        return $stmt->execute(['id' => $id]);
     }
}
