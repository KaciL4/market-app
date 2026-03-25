<?php

namespace App\Domain\Models;
use App\Helpers\Core\PDOService;
use PDO;
class UserModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
    public function createUser(array $data):bool{
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password,role, created_at) VALUES (:username, :email, :password, :role, CURRENT_TIMESTAMP())"
        );
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => 'user'
        ]);
    }
    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
    public function verifyPassword(string $inputPassword, string $hashedPassword): bool
    {
        return password_verify($inputPassword, $hashedPassword);
    }
    public function countAll(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return (int)$stmt->fetchColumn();
    }
    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT user_id, username, email, role, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteUser(int $userId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }
}
