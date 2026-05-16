<?php

use App\Helpers\ViewHelper;
$title = $data['title'] ?? "2FA Setup";
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 500px;">
    <h1>Enable Two-Factor Authentication</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= hs($error) ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h3>Setup Instructions:</h3>
        <ol>
            <li>Install Google Authenticator or Authy on your smartphone</li>
            <li>Scan the QR code below with the app</li>
            <li>Enter the 6-digit code from the app to verify</li>
        </ol>
    </div>

    <div class="text-center my-4 p-4 bg-white">
        <img src="<?= $qrCodeDataUri ?? '' ?>" alt="QR Code for 2FA Setup">
    </div>

    <div class="bg-light p-3 my-4">
        <p class="text-dark"><strong>Can't scan?</strong> Enter this code manually:</p>
        <code style="font-size: 1.2em; letter-spacing: 2px;"><?= hs($secret ?? '') ?></code>
    </div>

    <form method="POST" action="<?= APP_BASE_URL ?>/2fa/verify-and-enable">
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
                placeholder="Enter 6-digit code"
                style="font-size: 1.5em; letter-spacing: 5px;">
        </div>

        <button type="submit" class="btn btn-primary">Verify and Enable 2FA</button>
        <a href="<?= APP_BASE_URL ?>/dashboard" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php ViewHelper::loadAuthFooter(); ?>
