<?php

use App\Helpers\ViewHelper;
use App\Helpers\SessionManager;

global $translator;
$currentLocale = $translator->getLocale();

$title = $data['title'] ?? trans('nav.dashboard');
$has2FA = $data['has2FA'] ?? [];

ViewHelper::loadHeader($title);
?>

<div class="container mt-5" style="max-width: 800px;">
    <h1><?= hs(trans('nav.dashboard')) ?></h1>

    <div class="bg-light p-4 rounded mb-4">
        <h2><?= hs(trans('home.welcome')) ?>, <?= hs(SessionManager::get('user_first_name', 'User')) ?>!</h2>
        <p><?= hs(trans('auth.email')) ?>: <?= hs(SessionManager::get('user_email', '')) ?></p>
    </div>

    <hr>

    <h3>Security Settings</h3>

    <div class="border rounded p-4 bg-white">
        <h4>Two-Factor Authentication (2FA)</h4>

        <?php if ($has2FA): ?>
            <div class="alert alert-success">2FA is enabled</div>
            <p>Your account is protected with two-factor authentication.</p>
            <a href="<?= APP_BASE_URL ?>/2fa/disable?lang=<?= hs($currentLocale) ?>" class="btn btn-danger">Disable 2FA</a>
        <?php else: ?>
            <div class="alert alert-warning">2FA is disabled</div>
            <p>Enable 2FA to add an extra layer of security to your account.</p>
            <a href="<?= APP_BASE_URL ?>/2fa/setup?lang=<?= hs($currentLocale) ?>" class="btn btn-primary">Enable 2FA</a>
        <?php endif; ?>
    </div>

    <hr>

    <form method="POST" action="<?= APP_BASE_URL ?>/logout">
        <button type="submit" class="btn btn-secondary"><?= hs(trans('nav.logout')) ?></button>
    </form>
</div>

<?php ViewHelper::loadFooter(); ?>
