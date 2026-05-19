<?php

use App\Helpers\ViewHelper;

global $translator;
$currentLocale = $translator->getLocale();

$title = trans('items.title');

ViewHelper::loadItemHeader($title);
?>

<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-5 fw-bold text-white mb-3"><?= hs(trans('items.discover_unique')) ?></h1>
            <p class="lead text-secondary mb-4"><?= hs(trans('items.browse_subtitle')) ?></p>

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

            <div class="mt-3">
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
                            src="<?= !empty($item['file_path']) ? APP_BASE_URL . '/public/' . ltrim($item['file_path'], '/') : 'https://placehold.co/600x300/343a40/white?text=No+Image' ?>"
                            class="card-img-top"
                            style="height: 220px; object-fit:cover;">

                        <div class="card-body d-flex flex-column px-3 pt-3 pb-4">

                            <h5 class="fw-bold">
                                <?= hs($item['listing_product']) ?>
                            </h5>

                            <p class="small text-secondary">
                                <?= hs(mb_strimwidth($item['detail'], 0, 60, '...')) ?>
                            </p>

                            <p>
                                <?= hs(trans('items.seller')) ?>: <?= hs($item['username']) ?>
                            </p>

                            <div class="mt-auto mb-3">
                                <h5 class="text-primary fw-bold mb-0">
                                    $<?= number_format($item['price'], 2) ?>
                                </h5>
                            </div>

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

<script src="<?= APP_BASE_URL ?>/public/assets/js/item-search.js"></script>

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>