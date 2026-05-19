<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = $data['title'] ?? trans('auth.register');
$account_info = $data['account_info'] ?? [];
ViewHelper::loadAuthHeader($title);
?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card p-4 shadow-sm border-0">
        <h3 class="mb-2 text-center"><?= hs(trans('auth.create_account')) ?></h3>
        <p class="text-center text-muted mb-4"><?= hs(trans('auth.join_marketplace')) ?></p>

        <form method="POST" action="<?= APP_BASE_URL ?>/auth/register?lang=<?= hs($currentLocale) ?>">
            <div class="row">
                <div class="mb-3">
                    <label class="form-label"><?= hs(trans('auth.username')) ?></label>
                    <input type="text" name="username" class="form-control" value="<?= hs($account_info['username'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= hs(trans('auth.email')) ?></label>
                <input type="email" name="email" class="form-control" value="<?= hs($account_info['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><?= hs(trans('auth.password')) ?></label>
                <input type="password" name="password" class="form-control">
                <div class="form-text"><?= hs(trans('auth.password_help')) ?></div>
            </div>

            <div class="mb-4">
                <label class="form-label"><?= hs(trans('auth.confirm_password')) ?></label>
                <input type="password" name="confirm_password" class="form-control">
            </div>

            <button class="btn btn-success w-100 mb-3"><?= hs(trans('auth.register')) ?></button>
        </form>

        <p class="text-center mb-0">
            <small><?= hs(trans('auth.already_have_account')) ?> <a href="<?= APP_BASE_URL ?>/auth/login?lang=<?= hs($currentLocale) ?>"><?= hs(trans('auth.login')) ?></a></small>
        </p>
    </div>
</div>

<?php ViewHelper::loadAuthFooter(); ?>