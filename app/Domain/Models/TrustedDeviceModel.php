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
        return $this->execute("INSERT INTO trusted_devices (user_id, device_token, device_name, user_agent, ip_address, expires_at) VALUES (:user_id, :device_token, :device_name, :user_agent, :ip_address, :expires_at)", [
            'user_id' => $userId,
            'device_token' => $deviceToken,
            'device_name' => $deviceInfo['device_name'],
            'user_agent' => $deviceInfo['user_agent'],
            'ip_address' => $deviceInfo['ip_address'],
            'expires_at' => $deviceInfo['expires_at']
        ]);
    }

    public function isValid(string $deviceToken, int $userId): bool
    {
        // TODO: Check whether the token exists, belongs to the user,
        //       and has not expired.
        $isValid = $this->selectOne("SELECT device_token FROM trusted_devices WHERE device_token = :device_token AND user_id = :user_id AND expires_at > NOW() LIMIT 1", [
            'device_token' => $deviceToken,
            'user_id' => $userId
        ]);

        return (bool)$isValid;
    }

    public function updateLastUsed(string $deviceToken): bool
    {
        // TODO: Update the last_used_at timestamp for the device.
        $lastUsed = $this->execute("UPDATE trusted_devices SET last_used_at = NOW() WHERE device_token = :device_token", [
            'device_token' => $deviceToken
        ]);

        return $lastUsed > 0;
    }

    public function getAllByUserId(int $userId): array
    {
        // TODO: Return all non-expired trusted devices for the user.
        return $this->selectAll("SELECT user_id, device_token, device_name, user_agent, ip_address, expires_at FROM trusted_devices WHERE user_id = :user_id", [
            'user_id' => $userId
        ]);
    }

    public function revoke(int $deviceId, int $userId): bool
    {
        // TODO: Delete the device record. Include user_id in the
        //       WHERE clause to prevent unauthorized deletion.
        $affected = $this->execute("DELETE FROM trusted_devices WHERE id = :device_id AND user_id = :user_id", [
            'device_id' => $deviceId,
            'user_id' => $userId
        ]);

        return $affected > 0;
    }

    public function revokeAll(int $userId): bool
    {
        // TODO: Delete all trusted devices for the user.
        $affected = $this->execute("DELETE FROM trusted_devices WHERE user_id = :user_id", [
            'user_id' => $userId
        ]);

        return $affected > 0;
    }
}
