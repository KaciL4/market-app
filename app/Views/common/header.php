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
</head>

<body>

    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 px-1">
            <div class="container-fluid py-1">
                <a class="navbar-brand" href="#">
                    <span class="fw-bold">Resale Management System</span>
                </a>

                <form method="GET" action="" class="col-6 ms-4">
                    <div class="input-group">
                        <input type="text" id="searchInput" name="search" class="form-control border-start-0" placeholder="Search">
                        <button class="btn btn-primary" id="searchBtn">
                            <?= swarm_icon('tabler:search') ?>
                        </button>
                    </div>
                </form>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

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

                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_BASE_URL ?>/auth/login">
                                <div class="d-flex align-items-center">
                                    <span class="me-1"><?= swarm_icon('lucide:user') ?></span>
                                    <span class="fw-semibold">Sign in</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <div class="d-flex align-items-center">
                                    <span class="me-1"><?= swarm_icon('lucide:shopping-cart') ?></span> <span class="fw-semibold">Cart</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
