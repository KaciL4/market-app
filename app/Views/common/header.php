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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- TODO: include your CSS files here -->
    <style>
        body {
            padding-top: 65px;
            /* adjusts for fixed navbar */
        }
    </style>
</head>

<body>

    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary px-1 fixed-top">
            <div class="container-fluid py-1">
                <a class="navbar-brand" href="<?= APP_BASE_URL ?>">
                    <div class="d-flex align-items-center gap-1">
                        <?= swarm_icon('tabler:building-store') ?>
                        <span class="fw-bold">
                            Resale Management System
                        </span>
                    </div>
                </a>

                <div class="flex-grow-1 mx-lg-5 mx-md-3 d-none d-md-block">
                <form action="<?= APP_BASE_URL ?>/items" method="GET">
                    <div class="input-group">
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

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">
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
                                    <span class="fw-semibold"><?= htmlspecialchars($user['username']) ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= APP_BASE_URL ?>/dashboard">Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
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
                                        <span class="badge rounded-pill bg-danger">
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
    </header>
