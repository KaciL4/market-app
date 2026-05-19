<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? trans('admin.categories');

ViewHelper::loadAdminHeader($title);
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <div>
        <h2 class="mb-2"><?= hs(trans('admin.categories')) ?></h2>
        <p class="text-secondary mb-0"><?= hs(trans('admin.manage_categories')) ?></p>
    </div>
    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg me-2"></i> <?= hs(trans('admin.add_category')) ?>
    </button>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php
        $msg = match ($_GET['success']) {
            'added'   => trans('admin.category_added'),
            'edited'  => trans('admin.category_updated'),
            'deleted' => trans('admin.category_deleted'),
            default   => trans('admin.action_completed')
        };
        echo hs($msg);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php if (empty($categories)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-tags fs-1 d-block mb-2"></i>
            <?= hs(trans('admin.no_categories')) ?>
        </div>
    <?php else: ?>
        <?php
        $icons = [
            'Books'       => ['icon' => 'bi-book-fill',   'color' => 'text-warning'],
            'Furnitures'   => ['icon' => 'bi-house-fill',  'color' => 'text-info'],
            'Clothing'    => ['icon' => 'bi-bag-fill',    'color' => 'text-success'],
            'Electronics' => ['icon' => 'bi-laptop-fill', 'color' => 'text-primary'],
        ];
        $default = ['icon' => 'bi-tag-fill', 'color' => 'text-secondary'];
        ?>
        <?php foreach ($categories as $category): ?>
            <?php $iconData = $icons[$category['category_name']] ?? $default; ?>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-dark border border-secondary shadow-sm h-100 category-card">
                    <div class="card-body d-flex align-items-center justify-content-between p-4">

                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-secondary bg-opacity-25 p-3">
                                <i class="bi <?= $iconData['icon'] ?> fs-3 <?= $iconData['color'] ?>"></i>
                            </div>

                            <div>
                                <h5 class="mb-0 text-white"><?= htmlspecialchars($category['category_name']) ?></h5>
                                <small class="text-muted">ID: <?= htmlspecialchars($category['category_id']) ?></small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button
                                class="btn btn-outline-primary btn-sm rounded-circle p-2"
                                style="width:36px;height:36px;"
                                data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal"
                                data-id="<?= $category['category_id'] ?>"
                                data-name="<?= htmlspecialchars($category['category_name']) ?>"
                                title="<?= hs(trans('common.edit')) ?>">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button
                                class="btn btn-outline-danger btn-sm rounded-circle p-2"
                                style="width:36px;height:36px;"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteCategoryModal"
                                data-id="<?= $category['category_id'] ?>"
                                data-name="<?= htmlspecialchars($category['category_name']) ?>"
                                title="<?= hs(trans('common.delete')) ?>">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2 text-primary"></i><?= hs(trans('admin.add_category')) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= APP_BASE_URL ?>/admin/categories/add">
                <div class="modal-body">
                    <label class="form-label text-muted"><?= hs(trans('admin.category_name')) ?></label>
                    <input
                        type="text"
                        name="category_name"
                        class="form-control bg-secondary border-0 text-white"
                        placeholder="e.g. Electronics"
                        required>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= hs(trans('common.cancel')) ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i> <?= hs(trans('admin.add_category')) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2 text-primary"></i><?= hs(trans('admin.edit_category')) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editCategoryForm" action="">
                <div class="modal-body">
                    <label class="form-label text-muted"><?= hs(trans('admin.category_name')) ?></label>
                    <input
                        type="text"
                        name="category_name"
                        id="editCategoryName"
                        class="form-control bg-secondary border-0 text-white"
                        required>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= hs(trans('common.cancel')) ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> <?= hs(trans('profile.save_changes')) ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border border-danger">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= hs(trans('admin.confirm_delete')) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?= hs(trans('admin.delete_category_confirm')) ?>
                <strong id="deleteCategoryName" class="text-white"></strong>?
                <span class="text-danger"><?= hs(trans('admin.cannot_undone')) ?></span>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= hs(trans('common.cancel')) ?></button>
                <form id="deleteCategoryForm" method="POST" action="">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> <?= hs(trans('admin.yes_delete')) ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .category-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4) !important;
    }
</style>
<script>
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>
<script src="<?= APP_BASE_URL ?>/public/assets/js/admin/categories.js"></script>

<?php ViewHelper::loadAdminFooter(); ?>