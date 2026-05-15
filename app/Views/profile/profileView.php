<?php

use App\Helpers\ViewHelper;
use App\Helpers\SessionManager;
$user = SessionManager::get('user');
$userRole = SessionManager::get('role', $user['role'] ?? 'user');
$isAdmin = ($userRole === 'admin');

// Set the title and pass data to the header
$page_title = $data['title'] ?? ($isAdmin ? 'Admin Profile' : 'My Profile');
$title = $page_title;
$username = $data['username'] ?? SessionManager::get('username', 'User');
$profile = $data['profile'] ?? [];

ViewHelper::loadProfileHeader($title);
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2"><?= swarm_icon('lucide:square-user') ?></span>
                            <h4 class="mb-0"><?= hs($username) ?>'s Profile</h4>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (isset($profile) && $profile): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">User ID</label>
                            <p class="form-control-static"><?= hs($profile['user_id']) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <p class="form-control-static"><?= hs($profile['username']) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-static"><?= hs($profile['email']) ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <p>
                                <span class="badge <?= $profile['role'] === 'admin' ? 'bg-danger' : 'bg-primary' ?>">
                                    <?= hs($profile['role']) ?>
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Member Since</label>
                            <p class="form-control-static"><?= date('F j, Y', strtotime($profile['created_at'])) ?></p>
                        </div>

                        <hr>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                Edit Profile
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">Profile data not found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile popup -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= APP_BASE_URL ?>/profile/update" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= hs($profile['username'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= hs($profile['email'] ?? '') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
ViewHelper::loadProfileFooter();
?>
