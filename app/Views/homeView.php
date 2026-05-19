<?php

use App\Helpers\ViewHelper;
use App\Controllers\HomeController;

$page_title = trans('home.title');
$categories = $data['categories'] ?? [];
$recentItems = $data['recentItems'] ?? [];

global $translator;
$currentLocale = $translator->getLocale();

ViewHelper::loadHeader($page_title);
?>

<header>
    <nav class="navbar navbar-expand-lg bg-body-secondary py-1 ">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                        <?= hs(trans('nav.categories')) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a class="dropdown-item" href="<?= APP_BASE_URL ?>/items?category=<?= $category['category_id'] ?>&lang=<?= hs($currentLocale) ?>">
                                    <?= hs($category['category_name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div id="carouselCaptions" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>

    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="3000">
            <img src="<?= APP_BASE_URL . '/public/assets/images/carousels/carousel1.png' ?>" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block" style="left: 10%; right: auto; bottom: 50%; transform: translateY(50%); text-align: left;">
                <h1><strong><?= hs(trans('home.welcome')) ?></strong></h1>
                <h3><?= hs(trans('home.subtitle')) ?></h3>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="<?= APP_BASE_URL . '/public/assets/images/carousels/carousel2.png' ?>" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h1 style="color: white;"><strong><?= hs(trans('home.save_planet')) ?></strong></h1>
                <a href="<?= APP_BASE_URL ?>/items?lang=<?= hs($currentLocale) ?>" class="btn btn-primary btn-lg mt-3 rounded-pill">
                    <?= hs(trans('home.shop_now')) ?>
                </a>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="<?= APP_BASE_URL . '/public/assets/images/carousels/carousel3.png' ?>" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block" style="left: auto; right: 10%; bottom: 50%; transform: translateY(50%); text-align: left;">
                <h1 style="color: white;"><strong><?= hs(trans('home.member_or_new')) ?></strong></h1>
                <h2 style="color: white;"><?= hs(trans('home.sign_in_prompt')) ?></h2>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="3000">
            <img src="<?= APP_BASE_URL . '/public/assets/images/carousels/carousel4.png' ?>" class="d-block w-100" alt="">
            <div class="carousel-caption d-none d-md-block">
                <h1 style="color: white;"><strong><?= hs(trans('home.enjoy')) ?></strong></h1>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carouselCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<?php
$defaultImage = 'https://placehold.co/300x200/adb5bd/white?text=Other';

$categoryImage = [
    'Books' => APP_BASE_URL . '/public/assets/images/categories/book.jpg',
    'Book' => APP_BASE_URL . '/public/assets/images/categories/book.jpg',

    'Clothing' => APP_BASE_URL . '/public/assets/images/categories/clothing.jpg',
    'Clothes' => APP_BASE_URL . '/public/assets/images/categories/clothing.jpg',

    'Electronics' => APP_BASE_URL . '/public/assets/images/categories/electronics.jpg',

    'Furniture' => APP_BASE_URL . '/public/assets/images/categories/furniture.jpg',
    'Furnitures' => APP_BASE_URL . '/public/assets/images/categories/furnitures.jpg',

    'Study Materials' => APP_BASE_URL . '/public/assets/images/categories/studyMaterials.jpg',
    'Study Material' => APP_BASE_URL . '/public/assets/images/categories/studyMaterials.jpg',

    'Transportation' => APP_BASE_URL . '/public/assets/images/categories/transportation.webp',
    'Transport' => APP_BASE_URL . '/public/assets/images/categories/transportation.webp',

    'Other' => $defaultImage,
];
?>

<div class="container my-5">
    <h4 class="mb-4 fw-bold"><?= hs(trans('home.browse_category')) ?></h4>
    <div class="row g-4">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <?php $img = $categoryImage[$category['category_name']] ?? $defaultImage; ?>
                <div class="col-md-4 col-sm-6">
                    <a href="<?= APP_BASE_URL ?>/items?category=<?= $category['category_id'] ?>&lang=<?= hs($currentLocale) ?>"
                        class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm category-card">
                            <img
                                src="<?= $img ?>"
                                class="card-img-top"
                                alt="<?= hs($category['category_name']) ?>"
                                style="height: 250px; object-fit: cover;">
                            <div class="card-body text-center py-2">
                                <h5 class="mb-0 fw-bold"><?= hs($category['category_name']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">
                <i class="bi bi-tags fs-1 d-block mb-2"></i>
                <?= hs(trans('home.no_categories')) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$defaultRecentImage = 'https://placehold.co/300x200/adb5bd/white?text=Item';

$recentItemImages = [
    'Chair' => APP_BASE_URL . '/public/assets/images/items/chair.jpg',
    'Laptop' => APP_BASE_URL . '/public/assets/images/items/macbook.jpg',
    'Winter Jacket' => APP_BASE_URL . '/public/assets/images/items/jacket.jpg'
];
?>

<div class="container my-5">
    <h4 class="mb-4 fw-bold"><?= hs(trans('home.recent_items')) ?></h4>

    <div class="row g-4">
        <?php if (!empty($recentItems)): ?>
            <?php foreach ($recentItems as $item): ?>
                <div class="col-md-4">
                    <a href="<?= APP_BASE_URL ?>/items/<?= $item['item_id'] ?>?lang=<?= hs($currentLocale) ?>" class="text-decoration-none text-reset">
                        <div class="card h-100 border-0 shadow bg-dark text-white rounded-4 overflow-hidden item-card">
                            <img
                                src="<?= !empty($item['file_path']) ? APP_BASE_URL . '/public/' . ltrim($item['file_path'], '/') : ($recentItemImages[$item['listing_product']] ?? $defaultRecentImage) ?>"
                                class="card-img-top"
                                alt="<?= hs($item['listing_product']) ?>"
                                style="height: 200px; object-fit: cover;">

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-primary"><?= hs($item['listing_product']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= hs(mb_strimwidth($item['detail'], 0, 80, '...')) ?>
                                </p>
                                <div class="mt-auto">
                                    <p class="fw-bold fs-5 mb-0 text-dark">
                                        $<?= number_format((float)$item['price'], 2) ?>
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

<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>