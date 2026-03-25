<?php

use App\Helpers\ViewHelper;

$title = "Register";
ViewHelper::loadHeader($title);
?>

<div class="container mt-5" style="max-width: 600px;">
    <div class="card p-4 shadow-sm border-0">
        <h3 class="mb-2 text-center">Create Account</h3>
        <p class="text-center text-muted mb-4">Join the marketplace</p>

        <form method="POST" action="<?= APP_BASE_URL ?>/auth/register">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <button class="btn btn-success w-100 mb-3">Register</button>
        </form>

        <p class="text-center mb-0">
            <small>Already have an account? <a href="<?= APP_BASE_URL ?>/auth/login">Login</a></small>
        </p>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
