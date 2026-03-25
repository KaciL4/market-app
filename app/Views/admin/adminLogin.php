<?php

use App\Helpers\ViewHelper;

$title = "Admin Login";
ViewHelper::loadAdminHeader($title);
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card p-4 shadow-sm border-0">
        <h3 class="mb-2 text-center text-primary">Admin Panel</h3>
        <p class="text-center text-muted mb-4">Sign in to manage the system</p>

        <form method="POST" action="<?= APP_BASE_URL ?>/admin/login">

            <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter admin email" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button class="btn btn-primary w-100 mb-3">Login</button>
        </form>

        <p class="text-center mb-0">
            <small><a href="<?= APP_BASE_URL ?>/">Back to site</a></small>
        </p>
    </div>
</div>

<?php ViewHelper::loadAdminFooter(); ?>