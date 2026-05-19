<?php

use App\Helpers\ViewHelper;
use App\Helpers\FlashMessage;

$title = $data['title'] ?? trans('admin.upload_image');

ViewHelper::loadAdminHeader($title);
?>

<?= FlashMessage::render(); ?>

<div class="card mb-4">
    <div class="card-header">
        <h5><?= hs(trans('admin.upload_image')) ?></h5>
    </div>C
    <div class="card-body">
        <form method="POST" action="<?= APP_BASE_URL ?>/admin/upload" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="myfile" class="form-label"><?= hs(trans('admin.choose_file')) ?></label>
                <input
                    type="file"
                    class="form-control"
                    id="myfile"
                    name="myfile"
                    accept="image/*"
                    required>
                <div class="form-text">
                    <?= hs(trans('admin.select_image')) ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><?= hs(trans('admin.upload_file')) ?></button>
        </form>
    </div>
</div>

<?php if (!empty($files)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5><?= hs(trans('admin.uploaded_files')) ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach (array_reverse($files) as $filename): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <img
                                src="<?= APP_BASE_URL ?>/uploads/images/<?= htmlspecialchars($filename) ?>"
                                class="card-img-top"
                                alt="Uploaded image"
                                style="height: 200px; object-fit: cover;">

                            <div class="card-body">
                                <p class="card-text small text-muted">
                                    <?= htmlspecialchars($filename) ?>
                                </p>

                                <form method="POST" action="<?= APP_BASE_URL ?>/admin/upload/delete">
                                    <input type="hidden" name="filename" value="<?= htmlspecialchars($filename) ?>">

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <?= hs(trans('common.delete')) ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php ViewHelper::loadAdminFooter(); ?>
