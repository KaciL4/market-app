<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = $data['title'] ?? trans('items.upload_new_item');
$categories = $data['categories'] ?? [];

ViewHelper::loadItemHeader($title);
?>

<div class="container py-5" style="max-width: 800px;">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-4"><?= hs(trans('items.upload_new_item')) ?></h2>

            <form method="POST" action="<?= APP_BASE_URL ?>/items/upload?lang=<?= hs($currentLocale) ?>" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label"><?= hs(trans('items.listing_product')) ?></label>
                    <input type="text" name="listing_product" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= hs(trans('items.category')) ?></label>
                    <select name="category_id" class="form-select" required>
                        <option value=""><?= hs(trans('common.confirm')) ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= hs($category['category_id']) ?>">
                                <?= hs($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= hs(trans('items.price')) ?></label>
                    <input type="number" step="0.01" min="0.01" name="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><?= hs(trans('items.detail')) ?></label>
                    <textarea name="detail" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label"><?= hs(trans('items.upload_images')) ?></label>
                    <input type="file" name="item_image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <?= hs(trans('items.submit_listing')) ?>
                </button>

                <p class="text-warning mt-3 mb-0">
                    <?= hs(trans('items.pending_approval')) ?>
                </p>
            </form>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>