<?php

use App\Helpers\FlashMessage;
use App\Helpers\ViewHelper;

ViewHelper::loadAdminHeader($title); // ← this loads the sidebar automatically
?>

<div class="mb-4 border-bottom">
    <h2>User Management</h2>
    <p>View and manage all registered users</p>
</div>

<!-- Success message -->
<!-- <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        User deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?> -->
<?= FlashMessage::render() ?>

<!--Search Bar -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                id="searchInput"
                class="form-control border-start-0"
                placeholder="Search by username..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-primary" id="searchBtn">
                Search
            </button>
            <?php if (!empty($_GET['search'])): ?>
                <a href="<?= APP_BASE_URL ?>/admin/user_management" class="btn btn-secondary">
                    <i class="bi bi-x"></i> Clear
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Results count -->
<p class="text-muted small mb-2">
    <?php if (!empty($_GET['search'])): ?>
        Showing results for: <strong><?= htmlspecialchars($_GET['search']) ?></strong>
        — <?= count($users) ?> user(s) found
    <?php else: ?>
        Total users: <strong><?= count($users) ?></strong>
    <?php endif; ?>
</p>
<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                            <?php if (!empty($_GET['search'])): ?>
                                No user found matching <strong>"<?= htmlspecialchars($_GET['search']) ?>"</strong>.
                            <?php else: ?>
                                No users found.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_id']) ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['created_at']) ?></td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal"
                                    data-user-id="<?= $user['user_id'] ?>"
                                    data-username="<?= htmlspecialchars($user['username']) ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete user
                <strong id="modalUsername"></strong>?
                This action <span class="text-danger">cannot be undone</span>.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>

<script>
    const BASE_URL = "<?= APP_BASE_URL ?>";
</script>
<script src="<?= APP_BASE_URL ?>/public/assets/js/admin/userManagement.js">
</script>

<?php ViewHelper::loadAdminFooter();?>
