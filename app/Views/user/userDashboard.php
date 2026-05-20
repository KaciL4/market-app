<?php

use App\Helpers\SessionManager;
use App\Helpers\ViewHelper;

$title = $data['title'] ?? trans('nav.dashboard');

$totalUserItems = $data['totalUserItems'] ?? 0;
$totalCartItems = $data['totalCartItems'] ?? 0;

ViewHelper::loadUserHeader($title);
?>

<div class="ps-4 pt-3 mb-4">
    <h1 class="display-5 fw-bold text-white mb-2">
        <?= hs(trans('dashboard.welcome')) ?>, <?= hs(SessionManager::get('username', 'User')) ?>!
    </h1>

    <p class="lead text-secondary mb-3">
        <?= hs(trans('dashboard.subtitle')) ?>
    </p>
</div>

<!-- Dashboard cards -->
<div class="row g-4 mx-2 mb-5 pb-5">

    <!-- My Listings -->
    <div class="col-md-3">
        <div class="card stat-card border-primary bg-dark text-white rounded-4 p-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary mb-1"><?= hs(trans('card.my_listings')) ?></h6>
                    <h3 class="fw-bold mb-0"><?= $totalUserItems ?></h3>
                </div>
                <div class="text-primary fs-1">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Shopping Cart -->
    <div class="col-md-3">
        <div class="card stat-card border-success bg-dark text-white rounded-4 p-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-secondary mb-1"><?= hs(trans('card.shopping_cart')) ?></h6>
                    <h3 class="fw-bold mb-0"><?= $totalCartItems ?></h3>
                </div>
                <div class="text-success fs-1">
                    <i class="bi bi-cart"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Explore -->
    <div class="col-md-3">
        <a href="<?= APP_BASE_URL ?>/items" class="text-decoration-none">
            <div class="card stat-card border-info bg-info text-white rounded-4 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1"><?= hs(trans('card.browse_items')) ?></h6>
                        <h3 class="fw-bold mb-0"><?= hs(trans('card.explore')) ?></h3>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-arrow-up-right-circle-fill"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Upload Item -->
    <div class="col-md-3">
        <a href="<?= APP_BASE_URL ?>/items/upload" class="text-decoration-none">
            <div class="card stat-card border-danger bg-danger text-dark rounded-4 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1"><?= hs(trans('card.upload_item')) ?></h6>
                        <h3 class="text-white fw-bold mb-0"><?= hs(trans('card.sell')) ?></h3>
                    </div>
                    <div class="fs-1 text-white">
                        <i class="bi bi-upload"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<?php ViewHelper::loadUserFooter(); ?>
