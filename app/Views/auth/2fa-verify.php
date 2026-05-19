<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = $data['title'] ?? trans('security.two_factor_verification');
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 400px;">
    <h1><?= hs(trans('security.two_factor_verification')) ?></h1>
    <p><?= hs(trans('security.enter_6_digit')) ?></p>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= hs($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= APP_BASE_URL ?>/2fa/verify?lang=<?= hs($currentLocale) ?>">
        <div class="mb-3">
            <label for="code" class="form-label"><?= hs(trans('security.verification_code')) ?>:</label>
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
                <?= hs(trans('security.trust_device')) ?>
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100"><?= hs(trans('security.verify')) ?></button>
    </form>

    <div class="mt-4 text-center">
        <form method="GET" action="<?= APP_BASE_URL ?>/auth/logout">
            <input type="hidden" name="lang" value="<?= hs($currentLocale) ?>">
            <button type="submit" class="btn btn-link"><?= hs(trans('security.cancel_logout')) ?></button>
        </form>
    </div>
</div>

<?php ViewHelper::loadAuthFooter(); ?>