<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = trans('auth.login');
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card p-4 shadow-sm border-0">
        <h3 class="mb-2 text-center"><?= hs(trans('auth.user_login')) ?></h3>
        <p class="text-center text-muted mb-4"><?= hs(trans('auth.access_account')) ?></p>

        <form method="POST" action="<?= APP_BASE_URL ?>/auth/login?lang=<?= hs($currentLocale) ?>">
            <div class="mb-3">
                <label class="form-label"><?= hs(trans('auth.email')) ?></label>
                <input type="text" name="identifier" class="form-control" placeholder="<?= hs(trans('auth.email_or_username')) ?>">
            </div>

            <div class="mb-4">
                <label class="form-label"><?= hs(trans('auth.password')) ?></label>
                <input type="password" name="password" class="form-control" placeholder="<?= hs(trans('auth.enter_password')) ?>">
            </div>

            <button class="btn btn-primary w-100 mb-3"><?= hs(trans('auth.login')) ?></button>
        </form>

        <p class="text-center mb-0">
            <small><?= hs(trans('auth.no_account')) ?> <a href="<?= APP_BASE_URL ?>/auth/register?lang=<?= hs($currentLocale) ?>"><?= hs(trans('auth.register')) ?></a></small>
        </p>
    </div>
</div>

<?php ViewHelper::loadAuthFooter(); ?>