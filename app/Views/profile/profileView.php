<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? "Admin Profile";
$username = $data['username'] ?? '';
$profile = $data['profile'] ?? [];

ViewHelper::loadProfileHeader($title);
?>

<h5 class="card-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <span class="me-1"><?= swarm_icon('lucide:square-user') ?></span>
        <?= hs($username) ?>
    </div>

    <a href="<?= APP_ADMIN_URL ?>/dashboard" class="btn btn-outline-primary">Go Back</a>
</h5>

<div class="card-body">
    <p class="card-text">ID: <?= hs($profile['user_id']) ?></p>
    <p class="card-text">Email: <?= hs($profile['email']) ?></p>
    <p class="card-text">
        Role:
        <span class="badge bg-danger"><?= hs($profile['role']) ?></span>
    </p>
    <p class="card-text">Registered: <?= hs($profile['created_at']) ?></p>
</div>

<?php
ViewHelper::loadProfileFooter();
?>
