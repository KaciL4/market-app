<?php

use App\Helpers\ViewHelper;

ViewHelper::loadHeader($title);
?>

<div class="container mt-5" style="max-width: 400px;">
    <h1>Two-Factor Verification</h1>
    <p>Enter the 6-digit code from your authenticator app.</p>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= hs($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/2fa/verify">
        <div class="mb-3">
            <label for="code" class="form-label">Verification Code:</label>
            <input type="text"
                id="code"
                name="code"
                class="form-control text-center"
                pattern="[0-9]{6}"
                maxlength="6"
                required
                autofocus
                placeholder="000000"
                style="font-size: 2em; letter-spacing: 10px;">
        </div>

        <div class="mb-3">
            <label>
                <input type="checkbox" name="trust_device" value="1">
                Trust this device for 30 days
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Verify</button>
    </form>

    <div class="mt-4 text-center">
        <form method="POST" action="<?= APP_BASE_URL ?>/logout">
            <button type="submit" class="btn btn-link">Cancel and Logout</button>
        </form>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
