<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = trans('items.edit_item');
$item = $data['item'] ?? [];
$categories = $data['categories'] ?? [];

ViewHelper::loadItemHeader($title);
?>

<div class="container py-5" style="max-width: 800px;">
    <div class="card border-0 shadow rounded-4">
        <div class="card-body p-4">
            <h2 class="fw-bold mb-4"><?= hs(trans('items.edit_item')) ?></h2>

            <form method="POST" action="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>?lang=<?= hs($currentLocale) ?>">

                <div class="mb-3">
                    <label for="listing_product" class="form-label"><?= hs(trans('items.listing_product')) ?></label>
                    <input type="text" id="listing_product" name="listing_product" class="form-control" value="<?= hs($item['listing_product']) ?>">
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label"><?= hs(trans('items.category')) ?></label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value=""><?= hs(trans('common.confirm')) ?></option>

                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?= hs($category['category_id']) ?>"
                                <?= $category['category_id'] == $item['category_id'] ? 'selected' : '' ?>>
                                <?= hs($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="price" class="form-label"><?= hs(trans('items.price')) ?></label>
                    <input type="number" id="price" step="0.01" min="0.01" name="price" class="form-control" value="<?= hs($item['price']) ?>">
                </div>

                <div class="mb-3">
                    <label for="detail" class="form-label"><?= hs(trans('items.detail')) ?></label>
                    <textarea name="detail" id="detail" class="form-control" rows="4"><?= hs($item['detail']) ?></textarea>
                </div>

                <div class="d-flex justify-content-evenly align-items-center gap-2">
                    <button type="submit" class="btn btn-primary flex-fill py-2">
                        <?= hs(trans('items.update_listing')) ?>
                    </button>

                    <!-- TODO: Add cancel link back to the admin product list -->
                    <a class="btn btn-danger flex-fill py-2" href="<?= APP_BASE_URL ?>/my-items">
                        <?= hs(trans('items.cancel')) ?>
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php ViewHelper::loadFooter(); ?>
