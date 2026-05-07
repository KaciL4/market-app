<?php

declare(strict_types=1);

namespace App\Domain\Models;

class TrustedDeviceModel extends BaseModel
{
    public function create(int $userId, string $deviceToken, array $deviceInfo): int
    {
        // TODO: Insert a new record into the trusted_devices table with the
        //       user ID, device token, and device info (name, user agent,
        //       IP address, expiration date).
        
    }

    public function isValid(string $deviceToken, int $userId): bool
    {
        // TODO: Check whether the token exists, belongs to the user,
        //       and has not expired.
    }

    public function updateLastUsed(string $deviceToken): bool
    {
        // TODO: Update the last_used_at timestamp for the device.
    }

    public function getAllByUserId(int $userId): array
    {
        // TODO: Return all non-expired trusted devices for the user.
    }

    public function revoke(int $deviceId, int $userId): bool
    {
        // TODO: Delete the device record. Include user_id in the
        //       WHERE clause to prevent unauthorized deletion.
    }

    public function revokeAll(int $userId): bool
    {
        // TODO: Delete all trusted devices for the user.
    }
}
