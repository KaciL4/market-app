<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use PDO;

class UserModel extends BaseModel
{
    public function createUser(array $data): int
    {
        $sql = "INSERT INTO users (username, email, password, role, created_at)
        VALUES (:username, :email, :password, :role, :created_at)";

        $this->execute($sql, [
            'username' => $data['username'] ?? '',
            'email' => $data['email'] ?? '',
            'password' => password_hash($data['password'] ?? '', PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'customer',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // TODO: Return the new user's ID
        return (int)$this->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->selectOne(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            [
                ':email' => $email
            ]
        ) ?: null;
    }

    public function  findByUsername(string $username): ?array
    {
        return $this->selectOne(
            "SELECT * FROM users WHERE username = :username LIMIT 1",
            [
                ':username' => $username
            ]
        ) ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $email = $this->count(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                'email' => $email
            ]
        ) > 0;

        return $email;
    }

    public function usernameExists(string $username): bool
    {
        $username = $this->count(
            "SELECT COUNT(*) FROM users WHERE username = :username",
            [
                'username' => $username
            ]
        ) > 0;

        return $username;
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
