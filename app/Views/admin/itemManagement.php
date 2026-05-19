<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? trans('admin.item_management');

ViewHelper::loadAdminHeader($title);
?>

<?php
$defaultItemImage = 'https://placehold.co/600x300/adb5bd/white?text=Item';

$itemImages = [
    'Chair' => APP_BASE_URL . '/public/assets/images/items/chair.jpg',
    'Laptop' => APP_BASE_URL . '/public/assets/images/items/macbook.jpg',
    'Winter Jacket' => APP_BASE_URL . '/public/assets/images/items/jacket.jpg',
    'Bicycle' => APP_BASE_URL . '/public/assets/images/items/bicycle.jpg',
    'Textbook' => APP_BASE_URL . '/public/assets/images/items/textbook.jpg',
];
?>

<h2 class="mb-3"><?= hs(trans('admin.item_management')) ?></h2>

<form method="GET" action="<?= APP_BASE_URL ?>/admin/item_management" class="mb-4 d-flex gap-2">

    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="<?= hs(trans('nav.search_placeholder')) ?>"
        value="<?= htmlspecialchars($search ?? '') ?>">

    <button type="submit" class="btn btn-primary"><?= hs(trans('admin.search')) ?></button>

    <a href="<?= APP_BASE_URL ?>/admin/item_management" class="btn btn-secondary">
        <?= hs(trans('admin.clear')) ?>
    </a>

</form>

<div class="row">
    <?php if (!empty($items)) : ?>

        <?php foreach ($items as $item) : ?>
            <?php
            $itemImage = !empty($item['file_path'])
                ? APP_BASE_URL . '/public/' . ltrim($item['file_path'], '/')
                : ($itemImages[$item['listing_product']] ?? $defaultItemImage);
            ?>

            <div class="col-md-4 mb-4">
                <div class="card h-100">

                    <img
                        src="<?= $itemImage ?>"
                        class="card-img-top"
                        alt="<?= htmlspecialchars($item['listing_product']) ?>"
                        style="height: 300px; object-fit: cover;">

                    <div class="card-body">

                        <h5><?= htmlspecialchars($item['listing_product']) ?></h5>

                        <p><?= htmlspecialchars($item['detail']) ?></p>

                        <p><strong>$<?= number_format((float)$item['price'], 2) ?></strong></p>

                        <p class="text-muted small">
                            <?= htmlspecialchars($item['username']) ?>
                        </p>

                        <?php $status = strtolower($item['status'] ?? ''); ?>

                        <p><?= hs(trans('admin.status')) ?>: <?= hs($item['status'] ?? '') ?></p>

                        <a href="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                            <?= hs(trans('items.view_details')) ?>
                        </a>

                        <?php if ($status == 'pending') : ?>

                            <form method="POST" action="<?= APP_BASE_URL ?>/admin/item_management">
                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">

                                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm w-100 mb-2">
                                    <?= hs(trans('admin.approve')) ?>
                                </button>

                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm w-100">
                                    <?= hs(trans('admin.reject')) ?>
                                </button>
                            </form>

                        <?php else : ?>

                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                <?= hs(trans('admin.reviewed')) ?>
                            </button>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else : ?>

        <p><?= hs(trans('items.no_items')) ?></p>

    <?php endif; ?>
</div>

<?php ViewHelper::loadAdminFooter(); ?>