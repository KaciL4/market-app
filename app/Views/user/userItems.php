<?php

use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = trans('items.my_items');

ViewHelper::loadItemHeader($title);
?>

<div class="d-flex align-items-center justify-content-between py-3 px-4">
    <div>
        <h1 class="display-5 fw-bold text-white mb-2"><?= hs(trans('items.my_items')) ?></h1>
        <p class="lead text-secondary mb-0"><?= hs(trans('items.manage_items')) ?></p>
    </div>

    <a href="<?= APP_BASE_URL ?>/items/upload?lang=<?= hs($currentLocale) ?>" class="btn btn-primary px-3 py-2">
        <?= hs(trans('items.upload_new_item')) ?>
    </a>
</div>

<div class="container">
    <div class="row justify-content-center mb-5">

        <div class="col-lg-8">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary-subtle border-end-0 rounded-start-pill ps-4 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input
                    type="text"
                    id="liveSearchInput"
                    class="form-control bg-secondary bg-opacity-10 border-secondary-subtle border-start-0 border-end-0 text-white shadow-none"
                    placeholder="<?= hs(trans('nav.search_placeholder')) ?>"
                    autocomplete="off">
                <button class="btn btn-primary px-5 rounded-end-pill fw-bold" type="button" id="button-search">
                    <?= hs(trans('nav.search_btn')) ?>
                </button>
            </div>

            <div class="mt-3 text-center">
                <div id="loadingSpinner" class="spinner-border spinner-border-sm text-primary" style="display:none;">
                    <span class="visually-hidden"><?= hs(trans('common.loading')) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="searchResults" class="row g-4"></div>

<div id="defaultProducts" class="row gy-3">

    <?php if (empty($items)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
            <?= hs(trans('items.no_items')) ?>
        </div>
    <?php else: ?>
        <?php foreach ($items as $item): ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <a href="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>?lang=<?= hs($currentLocale) ?>" class="text-decoration-none">

                    <div class="card h-100 border-0 shadow bg-dark text-white rounded-4 overflow-hidden item-card">

                        <img
                            src="<?= !empty($item['file_path']) ? APP_BASE_URL . '/public/' . ltrim($item['file_path'], '/') : ($itemImages[$item['listing_product']] ?? $defaultItemImage) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($item['listing_product']) ?>"
                            style="height: 300px; object-fit: cover;">

                        <div class="card-body d-flex flex-column px-3 pt-3 pb-4">

                            <div class="d-flex justify-content-between mt-2">
                                <h5 class="fw-bold text-white">
                                    <?= hs($item['listing_product']) ?>
                                </h5>

                                <?php switch ($item['status']) {
                                    case "Pending":
                                ?>
                                        <span class="badge rounded-pill text-warning"><?= hs(trans('items.pending_approval')) ?></span>
                                    <?php break;
                                    case "Available":
                                    ?>
                                        <span class="badge rounded-pill bg-success"><?= hs(trans('items.available')) ?></span>
                                    <?php break;
                                    case "Sold":
                                    ?>
                                        <span class="badge rounded-pill bg-danger"><?= hs(trans('items.sold')) ?></span>
                                <?php break;
                                }
                                ?>
                            </div>

                            <p class="small text-secondary">
                                <?= hs(mb_strimwidth($item['detail'], 0, 60, '...')) ?>
                            </p>

                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div class="mt-auto mb-3">
                                    <h5 class="text-primary fw-bold mb-0">
                                        $<?= number_format($item['price'], 2) ?>
                                    </h5>
                                </div>

                                <p class="small text-secondary">
                                    <?= hs($item['listing_date']) ?>
                                </p>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-2 gap-2">
                                <a class="btn btn-primary flex-fill" href="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>/edit">
                                    <?= hs(trans('common.edit')) ?>
                                </a>

                                <a onclick="confirmDeleteItem(<?= hs($item['item_id']) ?>, '<?= hs($item['listing_product']) ?>');" class="btn btn-danger flex-fill">
                                    <?= hs(trans('common.delete')) ?>
                                </a>
                            </div>

                            <?php if (hs($item['status']) == "Pending"): ?>
                                <p class="text-warning mt-3 fs-6">
                                    <?= hs(trans('items.waiting_admin')) ?>
                                </p>
                            <?php endif ?>
                        </div>
                    </div>

                </a>
            </div>
        <?php endforeach ?>
    <?php endif ?>

</div>

<script>
    window.APP_BASE_URL = '<?= APP_BASE_URL ?>';
</script>

<script src="<?= APP_BASE_URL ?>/public/assets/js/my-item-search.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= APP_BASE_URL ?>/public/assets/js/delete-item.js"></script>

<?php
ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
