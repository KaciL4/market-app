<?php

use App\Helpers\SessionManager;

$cart = SessionManager::get('cart', []);
$cartCount = 0;
foreach ($cart as $item) {
    $cartCount += $item['quantity'];
}
// Check if user is logged in
$user = SessionManager::get('user');
$isLoggedIn = !empty($user);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="<?= asset_url('/css/style.css') ?>" rel="stylesheet">
</head>

<body>

    <header>
        <!-- Main Navbar - Top Layer -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary px-1 fixed-top">
            <div class="container-fluid py-2">
                <a class="navbar-brand" href="<?= APP_BASE_URL ?>">
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
                    <!-- Search Bar -->
                    <div class="flex-grow-1 mx-lg-5 mx-md-3">
                        <form action="<?= APP_BASE_URL ?>/items" method="GET" class="d-flex">
                            <div class="input-group w-100">
                                <span class="input-group-text bg-body border-end-0 rounded-start-pill ps-3">
                                    <i class="bi bi-search text-secondary"></i>
                                </span>
                                <input type="text" name="search"
                                    class="form-control bg-body border-start-0 border-end-0 py-2 shadow-none"
                                    placeholder="Search for anything..."
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button class="btn btn-primary px-4 rounded-end-pill fw-semibold" type="submit">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right side navigation -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <?= swarm_icon('tabler:world') ?>
                            </a>
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
                                    <li><a class="dropdown-item" href="<?= APP_BASE_URL ?>/profile">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="<?= APP_BASE_URL ?>/auth/logout">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= APP_BASE_URL ?>/auth/login">
                                    <div class="d-flex align-items-center gap-1">
                                        <?= swarm_icon('lucide:user') ?>
                                        <span class="fw-semibold">Sign in</span>
                                    </div>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_BASE_URL ?>/cart">
                                <div class="d-flex align-items-center gap-1 position-relative">
                                    <?= swarm_icon('lucide:shopping-cart') ?>
                                    <span class="fw-semibold">Cart</span>
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

        <!-- Second Layer Navigation - Sub Navbar -->
        <nav class="navbar navbar-expand-lg bg-secondary bg-opacity-25 px-1" style="margin-top: 70px;">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#subNavbar" aria-controls="subNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="subNavbar">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4 py-2">
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= basename($_SERVER['REQUEST_URI']) == 'dashboard' ? 'active' : '' ?>" href="<?= APP_BASE_URL ?>/dashboard">
                                <div class="d-flex align-items-center gap-2">
                                    <?= swarm_icon('tabler:home') ?>
                                    <span>Home</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold <?= strpos($_SERVER['REQUEST_URI'], '/items') !== false ? 'active' : '' ?>" href="<?= APP_BASE_URL ?>/items">
                                <div class="d-flex align-items-center gap-2">
                                    <?= swarm_icon('tabler:list-search') ?>
                                    <span>Browse Items</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="#">
                                <div class="d-flex align-items-center gap-2">
                                    <?= swarm_icon('tabler:upload') ?>
                                    <span>Upload Item</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" href="<?= APP_BASE_URL ?>/my-items">
                                <div class="d-flex align-items-center gap-2">
                                    <?= swarm_icon('tabler:box') ?>
                                    <span>My Items</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <main class="user-content">