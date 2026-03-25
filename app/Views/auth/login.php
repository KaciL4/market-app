<?php

use App\Helpers\ViewHelper;

$title = "Login";
ViewHelper::loadHeader($title);
?>

<div class="container mt-5" style="max-width: 500px;">
    <div class="card p-4 shadow-sm border-0">
        <h3 class="mb-2 text-center">User Login</h3>
        <p class="text-center text-muted mb-4">Access your account</p>

        <form method="POST" action="<?= APP_BASE_URL ?>/auth/login">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button class="btn btn-primary w-100 mb-3">Login</button>
        </form>

        <p class="text-center mb-0">
            <small>No account? <a href="<?= APP_BASE_URL ?>/auth/register">Register</a></small>
        </p>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
