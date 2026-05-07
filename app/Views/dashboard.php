<?php

use App\Helpers\ViewHelper;
use App\Helpers\SessionManager;

$has2FA = $data['has2FA'] ?? []; // Need to change after Controller and Middleware is done
ViewHelper::loadHeader($title);
?>

<div class="container mt-5" style="max-width: 800px;">
    <h1>Dashboard</h1>

    <div class="bg-light p-4 rounded mb-4">
        <h2>Welcome, <?= hs(SessionManager::get('user_first_name', 'User')) ?>!</h2>
        <p>Email: <?= hs(SessionManager::get('user_email', '')) ?></p>
    </div>

    <hr>

    <h3>Security Settings</h3>

    <div class="border rounded p-4 bg-white">
        <h4>Two-Factor Authentication (2FA)</h4>

        <?php if ($has2FA): ?>
            <div class="alert alert-success">2FA is enabled</div>
            <p>Your account is protected with two-factor authentication.</p>
            <a href="<?= APP_BASE_URL ?>/2fa/disable" class="btn btn-danger">Disable 2FA</a>
        <?php else: ?>
            <div class="alert alert-warning">2FA is disabled</div>
            <p>Enable 2FA to add an extra layer of security to your account.</p>
            <a href="<?= APP_BASE_URL ?>/2fa/setup" class="btn btn-primary">Enable 2FA</a>
        <?php endif; ?>
    </div>

    <hr>

    <form method="POST" action="<?= APP_BASE_URL ?>/logout">
        <button type="submit" class="btn btn-secondary">Logout</button>
    </form>
</div>

<?php ViewHelper::loadFooter(); ?>
