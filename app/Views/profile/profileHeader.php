<?php

use App\Helpers\SessionManager;

$page_title = $page_title ?? $title ?? 'Edit Profile';

global $translator;
$currentLocale = $translator->getLocale();
$availableLocales = $translator->getAvailableLocales();

$cart = SessionManager::get('cart', []);
$cartCount = 0;
foreach ($cart as $item) {
    $cartCount += $item['quantity'];
}

// Check if user is logged in
$user = SessionManager::get('user');
$isLoggedIn = !empty($user);

$userRole = strtolower(SessionManager::get('user_role', SessionManager::get('role', $user['role'] ?? 'user')));
$dashboardUrl = $userRole === 'admin'
    ? APP_BASE_URL . '/admin/dashboard?lang=' . hs($currentLocale)
    : APP_BASE_URL . '/dashboard?lang=' . hs($currentLocale);
?>
<!DOCTYPE html>
<html lang="<?= hs($currentLocale) ?>" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= hs($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="<?= asset_url('/css/style.css') ?>" rel="stylesheet">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary px-1 fixed-top">
            <div class="container-fluid py-2">
                <a class="navbar-brand" href="<?= $isLoggedIn ? $dashboardUrl : APP_BASE_URL ?>">
                    <div class="d-flex align-items-center gap-1">
                        <?= swarm_icon('tabler:building-store') ?>
                        <span class="fw-bold">
                            Resale Management System
                        </span>
                    </div>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <<<<<<< Updated upstream
                        <div class="flex-grow-1 mx-lg-5 mx-md-3">
                        <form action="<?= APP_BASE_URL ?>/items" method="GET" class="d-flex">
                            <input type="hidden" name="lang" value="<?= hs($currentLocale) ?>">

                            <div class="input-group w-100">
                                <span class="input-group-text bg-body border-end-0 rounded-start-pill ps-3">
                                    <i class="bi bi-search text-secondary"></i>
                                </span>
                                <input type="text" name="search"
                                    class="form-control bg-body border-start-0 border-end-0 py-2 shadow-none"
                                    placeholder="<?= hs(trans('nav.search_placeholder')) ?>"
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button class="btn btn-primary px-4 rounded-end-pill fw-semibold" type="submit">
                                    <?= hs(trans('nav.search_btn')) ?>
                                </button>
                            </div>
                        </form>
                </div>
                =======
                >>>>>>> Stashed changes

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <?= swarm_icon('tabler:world') ?>
                            <span class="fw-semibold"><?= strtoupper($currentLocale) ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php foreach ($availableLocales as $locale): ?>
                                <li>
                                    <a class="dropdown-item <?= $locale === $currentLocale ? 'active' : '' ?>"
                                        href="<?= hs(strtok($_SERVER['REQUEST_URI'], '?') . '?lang=' . $locale) ?>">
                                        <?= $locale === 'en' ? '🇬🇧 English' : '🇫🇷 Français' ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <?= swarm_icon('lucide:sun') ?>
                        </a>
                    </li>

                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                                <?= swarm_icon('lucide:user') ?>
                                <span class="fw-semibold"><?= hs($user['username']) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <<<<<<< Updated upstream
                                    <li>
                                    <a class="dropdown-item" href="<?= $dashboardUrl ?>">
                                        <?= $userRole === 'admin' ? hs(trans('admin.dashboard')) : hs(trans('nav.dashboard')) ?>
                                    </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="<?= $userRole === 'admin' ? APP_BASE_URL . '/admin/profile?lang=' . hs($currentLocale) : APP_BASE_URL . '/profile?lang=' . hs($currentLocale) ?>">
                                <?= hs(trans('nav.profile')) ?>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= APP_BASE_URL ?>/auth/logout?lang=<?= hs($currentLocale) ?>"><?= hs(trans('nav.logout')) ?></a></li>
                        =======
                        <li><a class="dropdown-item" href="<?= APP_BASE_URL ?>/dashboard">Dashboard</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?= APP_BASE_URL ?>/profile">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= APP_BASE_URL ?>/auth/logout">Logout</a></li>
                        >>>>>>> Stashed changes
                </ul>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= APP_BASE_URL ?>/auth/login?lang=<?= hs($currentLocale) ?>">
                        <div class="d-flex align-items-center gap-1">
                            <?= swarm_icon('lucide:user') ?>
                            <span class="fw-semibold"><?= hs(trans('nav.sign_in')) ?></span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="<?= APP_BASE_URL ?>/cart?lang=<?= hs($currentLocale) ?>">
                    <div class="d-flex align-items-center gap-1 position-relative">
                        <?= swarm_icon('lucide:shopping-cart') ?>
                        <span class="fw-semibold"><?= hs(trans('nav.cart')) ?></span>
                        <?php if ($cartCount > 0): ?>
                            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            </li>
            </ul>
            </div>
            </div>
        </nav>
        <div style="margin-top: 70px;"></div>
    </header>

    <<<<<<< Updated upstream=======>>>>>>> Stashed changes
        <main class="user-content">