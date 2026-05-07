<?php

declare(strict_types=1);

namespace App\Domain\Models;

class TwoFactorAuthModel extends BaseModel
{
    public function findByUserId(int $userId): ?array
    {
        // TODO: Query the two_factor_auth table to find the record
        //       for the given user.
        return $this->selectOne("SELECT * FROM two_factor_auth WHERE user_id = :user_id LIMIT 1",
            [
                'user_id' => $userId
            ]
        );
    }

    public function create(int $userId, string $secret): int
    {
        // TODO: Insert a new 2FA record with the user ID and secret.
        //       Return the ID of the created record.
        $this->execute("INSERT INTO two_factor_auth (user_id, secret) VALUES (:user_id, :secret",
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
        $enable = $this->execute("UPDATE two_factor_auth SET enabled = :enabled, enabled_at = :enabled_at WHERE user_id = :user_id",
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
        $disable = $this->execute("UPDATE two_factor_auth SET enabled = :enabled WHERE user_id = :user_id",
            [
                'user_id' => $userId,
                'enabled' => 0
            ]
        );

        return $disable > 0;
    }

    public function isEnabled(int $userId): bool
    {
        // TODO: Check whether the user has 2FA enabled.
        $isEnabled = $this->selectOne("SELECT * FROM WHERE user_id = :user_id AND enabled = :enabled LIMIT 1",
            [
                'user_id' => $userId,
                'enabled' => 1
            ]
        );

        return $isEnabled > 0;
    }

    public function getSecret(int $userId): ?string
    {
        // TODO: Return the TOTP secret for the given user.
        return (string)$this->selectOne("SELECT secret FROM two_factor_auth WHERE user_id = :user_id LIMIT 1",
            [
                'user_id' => $userId
            ]
        );
    }
}
