<?php

use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$title = $data['title'] ?? "User Dashboard";

ViewHelper::loadUserHeader($title);
?>

<div class="ps-4 pt-4 mb-4">
    <h2 class="fw-bold">Welcome, <?= hs(SessionManager::get('username', 'User')) ?>!</h2>
    <p class="text-secondary mb-3">Your Student Marketplace Dashboard</p>
</div>

<?php ViewHelper::loadUserFooter(); ?>
