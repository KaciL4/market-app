<?php

use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$title = $data['title'] ?? trans('nav.dashboard');

ViewHelper::loadUserHeader($title);
?>

<div class="ps-4 pt-3 mb-4">
    <h1 class="display-5 fw-bold text-white mb-2">
        <?= hs(trans('dashboard.welcome')) ?>, <?= hs(SessionManager::get('username', 'User')) ?>!
    </h1>

    <p class="lead text-secondary mb-3">
        <?= hs(trans('dashboard.subtitle')) ?>
    </p>
</div>

<?php ViewHelper::loadUserFooter(); ?>
