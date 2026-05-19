<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = $data['title'] ?? trans('security.two_factor_setup');
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 500px;">
    <h1><?= hs(trans('security.enable_2fa')) ?></h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= hs($error) ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h3><?= hs(trans('security.setup_instructions')) ?>:</h3>
        <ol>
            <li><?= hs(trans('security.install_app')) ?></li>
            <li><?= hs(trans('security.scan_qr')) ?></li>
            <li><?= hs(trans('security.enter_code_verify')) ?></li>
        </ol>
    </div>

    <div class="text-center my-4 p-4 bg-white">
        <img src="<?= $qrCodeDataUri ?? '' ?>" alt="<?= hs(trans('security.qr_alt')) ?>">
    </div>

    <div class="bg-light p-3 my-4">
        <p class="text-dark"><strong><?= hs(trans('security.cant_scan')) ?></strong> <?= hs(trans('security.enter_manual')) ?></p>
        <code style="font-size: 1.2em; letter-spacing: 2px;"><?= hs($secret ?? '') ?></code>
    </div>

    <form method="POST" action="<?= APP_BASE_URL ?>/2fa/verify-and-enable?lang=<?= hs($currentLocale) ?>">
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
                placeholder="<?= hs(trans('security.enter_6_digit_placeholder')) ?>"
                style="font-size: 1.5em; letter-spacing: 5px;">
        </div>

        <button type="submit" class="btn btn-primary"><?= hs(trans('security.verify_enable')) ?></button>
        <a href="<?= APP_BASE_URL ?>/dashboard?lang=<?= hs($currentLocale) ?>" class="btn btn-secondary"><?= hs(trans('common.cancel')) ?></a>
    </form>
</div>

<?php ViewHelper::loadAuthFooter(); ?>