<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? "Disable 2FA";
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 400px;">
    <h1>Disable Two-Factor Authentication</h1>
    <p>Enter your password to confirm disabling 2FA.</p>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= hs($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/2fa/disable">
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">Disable 2FA</button>
        <a href="<?= APP_BASE_URL ?>/dashboard" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php ViewHelper::loadAuthFooter(); ?>