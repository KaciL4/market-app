<?php

use App\Helpers\ViewHelper;

$title = $data['title'] ?? 'Item Management';

ViewHelper::loadAdminHeader($title);

?>

<?php
$defaultItemImage = 'https://placehold.co/600x300/adb5bd/white?text=Item';

$itemImages = [
    'Chair' => APP_BASE_URL . '/public/assets/images/items/chair.jpg',
    'Laptop' => APP_BASE_URL . '/public/assets/images/items/macbook.jpg',
    'Winter Jacket' => APP_BASE_URL . '/public/assets/images/items/jacket.jpg'
];
?>

<h2 class="mb-3">Item Management</h2>

<form method="GET" action="<?= APP_BASE_URL ?>/admin/item_management" class="mb-4 d-flex gap-2">

    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search..."
        value="<?= htmlspecialchars($search ?? '') ?>">

    <button type="submit" class="btn btn-primary">Search</button>

    <a href="<?= APP_BASE_URL ?>/admin/item_management" class="btn btn-secondary">
        Clear
    </a>

</form>

<div class="row">
    <?php if (!empty($items)) : ?>

        <?php foreach ($items as $item) : ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">

                    <img
                        src="<?= $itemImages[$item['listing_product']] ?? $defaultItemImage ?>"
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

                        <p>Status: <?= hs($item['status']) ?></p>

                        <a href="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>" class="btn btn-outline-light btn-sm w-100 mb-2">
                            View Details
                        </a>

                        <?php if ($status == 'pending') : ?>

                            <form method="POST" action="<?= APP_BASE_URL ?>/admin/item_management">
                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">

                                <button name="action" value="approve" class="btn btn-success btn-sm w-100 mb-2">
                                    Approve
                                </button>

                                <button name="action" value="reject" class="btn btn-danger btn-sm w-100">
                                    Reject
                                </button>
                            </form>

                        <?php else : ?>

                            <button class="btn btn-secondary btn-sm w-100" disabled>
                                Reviewed
                            </button>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else : ?>

        <p>No items found.</p>

    <?php endif; ?>
</div>

<?php ViewHelper::loadAdminFooter(); ?>
