<?php

declare(strict_types=1);

namespace App\Domain\Models;

class TwoFactorAuthModel extends BaseModel
{
    public function findByUserId(int $userId): ?array
    {
        // TODO: Query the two_factor_auth table to find the record
        //       for the given user.
        $row =  $this->selectOne(
            "SELECT * FROM two_factor_auth WHERE user_id = :user_id LIMIT 1",
            [
                'user_id' => $userId
            ]
        );

        return $row ?: null;
    }

    public function create(int $userId, string $secret): int
    {
        // TODO: Insert a new 2FA record with the user ID and secret.
        //       Return the ID of the created record.
        $this->execute(
            "INSERT INTO two_factor_auth (user_id, secret) VALUES (:user_id, :secret)",
            [
                'user_id' => $userId,
                'secret' => $secret
            ]
        );

        return (int)$this->lastInsertId();
    }

    public function enable(int $userId): bool
    {
        // TODO: Update the record to mark 2FA as enabled and set
        //       the enabled timestamp.
        $enable = $this->execute(
            "UPDATE two_factor_auth SET enabled = :enabled, enabled_at = :enabled_at WHERE user_id = :user_id",
            [
                'user_id' => $userId,
                'enabled' => 1,
                'enabled_at' => date('Y-m-d H:i:s')
            ]
        );

        return $enable > 0;
    }

    public function disable(int $userId): bool
    {
        // TODO: Update the record to mark 2FA as disabled.
        return $this->execute(
            "DELETE FROM two_factor_auth WHERE user_id = :user_id",
            ['user_id' => $userId]
        ) > 0;
    }

    public function isEnabled(int $userId): bool
    {
        // TODO: Check whether the user has 2FA enabled.
        $row = $this->selectOne(
            "SELECT enabled FROM two_factor_auth WHERE user_id = :user_id LIMIT 1",
            [
                'user_id' => $userId,
            ]
        );

        if (!$row) {
            return false;
        }

        return (bool)$row['enabled'];
    }

    public function getSecret(int $userId): ?string
    {
        // TODO: Return the TOTP secret for the given user.
        $secret = $this->selectOne(
            "SELECT secret FROM two_factor_auth WHERE user_id = :user_id LIMIT 1",
            [
                'user_id' => $userId
            ]
        );
        return $secret['secret'] ?? null;
    }
}
