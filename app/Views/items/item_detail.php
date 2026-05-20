<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$item = $data['item'] ?? [];
$title = $data['title'] ?? ($item['listing_product'] ?? trans('items.title'));

ViewHelper::loadHeader($title);
?>

<div class="container my-5">
    <div class="row g-5 align-items-center">
        <div class="col-md-6">

            <?php
            $itemImageFallbacks = [
                'Chair' => APP_BASE_URL . '/public/assets/images/items/chair.jpg',
                'Laptop' => APP_BASE_URL . '/public/assets/images/items/macbook.jpg',
                'Winter Jacket' => APP_BASE_URL . '/public/assets/images/items/jacket.jpg',
                'Bicycle' => APP_BASE_URL . '/public/assets/images/items/bicycle.jpg',
                'Textbook' => APP_BASE_URL . '/public/assets/images/items/textbook.jpg',
            ];

            $mainImage = !empty($item['file_path'])
                ? APP_BASE_URL . '/public/' . ltrim($item['file_path'], '/')
                : ($itemImageFallbacks[$item['listing_product']] ?? 'https://placehold.co/600x400/343a40/white?text=No+Image');
            ?>

            <img
                src="<?= $mainImage ?>"
                class="img-fluid"
                alt="<?= htmlspecialchars($item['listing_product']) ?>"
                style="height: 500px; object-fit: cover; width: 100%;">

        </div>

        <div class="col-md-6">
            <div class="d-flex flex-column">

                <h1 class="mb-3"><?= hs($item['listing_product']) ?></h1>

                <h3 class="text-primary mb-5">
                    $<?= number_format($item['price'], 2) ?>
                </h3>

                <div class="d-grid mb-4">
                    <form action="<?= APP_BASE_URL ?>/cart/add?lang=<?= hs($currentLocale) ?>" method="POST">
                        <input type="hidden" name="item_id" value="<?= (int)$item['item_id'] ?>">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-cart-plus me-2"></i>
                            <?= hs(trans('items.add_to_cart')) ?>
                        </button>
                    </form>
                </div>

                <hr class="border-secondary">

                <h4 class="accordion-header"><?= hs(trans('items.detail')) ?></h4>

                <p class="text-secondary mb-4"><?= hs($item['detail']) ?></p>

                <ul class="list-unstyled mb-4">
                    <li class="mb-2">
                        <strong><?= hs(trans('items.seller')) ?>:</strong>
                        <?= hs($item['username'] ?? 'Unknown') ?>
                    </li>

                    <li class="mb-2">
                        <strong><?= hs(trans('items.status')) ?>:</strong>
                        <?php switch ($item['status']) {
                            case "Pending": ?>
                                <span class="badge rounded-pill bg-warning"><?= hs(trans('items.pending_approval')) ?></span>
                            <?php break;
                            case "Available": ?>
                                <span class="badge rounded-pill bg-success"><?= hs(trans('items.available')) ?></span>
                            <?php break;
                            case "Sold": ?>
                                <span class="badge rounded-pill bg-danger"><?= hs(trans('items.sold')) ?></span>
                        <?php break;
                        } ?>
                    </li>

                    <li class="mb-2">
                        <strong><?= hs(trans('items.listed_on')) ?>:</strong>
                        <?= hs($item['listing_date']) ?>
                    </li>

                    <li class="mb-2">
                        <strong><?= hs(trans('items.category')) ?>:</strong>
                        <?= hs($item['category_name']) ?>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <?php
    $defaultRecentImage = 'https://placehold.co/300x200/adb5bd/white?text=Item';

    $recentItemImages = [
        'Chair' => APP_BASE_URL . '/public/assets/images/items/chair.jpg',
        'Laptop' => APP_BASE_URL . '/public/assets/images/items/macbook.jpg',
        'Winter Jacket' => APP_BASE_URL . '/public/assets/images/items/jacket.jpg',
        'Bicycle' => APP_BASE_URL . '/public/assets/images/items/bicycle.jpg',
        'Textbook' => APP_BASE_URL . '/public/assets/images/items/textbook.jpg',
    ];
    ?>

    <div class="container my-5">
        <h4 class="mb-4 fw-bold"><?= hs(trans('home.recent_items')) ?></h4>

        <div class="row g-4">
            <?php if (!empty($recentItems)): ?>
                <?php foreach ($recentItems as $recentItem): ?>
                    <?php
                    $recentImage = !empty($recentItem['file_path'])
                        ? APP_BASE_URL . '/public/' . ltrim($recentItem['file_path'], '/')
                        : ($recentItemImages[$recentItem['listing_product']] ?? $defaultRecentImage);
                    ?>

                    <div class="col-md-4">
                        <a href="<?= APP_BASE_URL ?>/items/<?= $recentItem['item_id'] ?>?lang=<?= hs($currentLocale) ?>" class="text-decoration-none text-reset">
                            <div class="card h-100 border-0 shadow bg-dark text-white rounded-4 overflow-hidden item-card">
                                <img
                                    src="<?= $recentImage ?>"
                                    class="card-img-top"
                                    alt="<?= hs($recentItem['listing_product']) ?>"
                                    style="height: 200px; object-fit: cover;">

                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-primary"><?= hs($recentItem['listing_product']) ?></h5>
                                    <p class="card-text text-muted small">
                                        <?= hs(mb_strimwidth($recentItem['detail'], 0, 80, '...')) ?>
                                    </p>
                                    <div class="mt-auto">
                                        <p class="fw-bold fs-5 mb-0 text-dark">
                                            $<?= number_format((float)$recentItem['price'], 2) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">
                    <?= hs(trans('home.no_recent_items')) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body p-4 text-center">
                        <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>

                        <h4 class="fw-bold"><?= hs(trans('cart.item_added')) ?></h4>
                        <p class="text-muted"><?= hs(trans('cart.new_item_in_cart')) ?></p>

                        <div class="d-grid gap-2 mt-4">
                            <a href="<?= APP_BASE_URL ?>/cart?lang=<?= hs($currentLocale) ?>" class="btn btn-primary rounded-pill py-2 fw-bold">
                                <?= hs(trans('cart.view_cart')) ?>
                            </a>
                            <button type="button" class="btn btn-outline-secondary rounded-pill py-2" data-bs-dismiss="modal">
                                <?= hs(trans('cart.continue_shopping')) ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        </script>
    <?php endif; ?>

    <?php
    ViewHelper::loadJsScripts();
    ViewHelper::loadFooter();
    ?>
