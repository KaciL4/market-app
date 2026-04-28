<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="<?= asset_url('/css/style.css') ?>" rel="stylesheet">
    <style>
        .admin-sidebar {
            min-height: 100vh;
            background-color: #374047;
        }

        .admin-sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }

        .admin-content {
            background-color: #212d39be;
            min-height: 100vh;
        }

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <?php
    // Helper to mark the active sidebar link

    use App\Helpers\SessionManager;

    $currentUri = $_SERVER['REQUEST_URI'];
    function isActive(string $path): string
    {
        global $currentUri;
        return str_contains($currentUri, $path) ? 'active' : '';
    }
    ?>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 px-1">
            <div class="container-fluid py-1">
                <a class="navbar-brand" href="<?= APP_BASE_URL ?>">
                    <div class="d-flex align-items-center gap-1">
                        <?= swarm_icon('tabler:building-store') ?>
                        <span class="fw-bold">
                            Resale Management System
                        </span>
                    </div>
                </a>

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
                                <?= swarm_icon('tabler:sun-high') ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_ADMIN_URL ?>/profile"> <!-- Click username will redirect to Admin Profile page -->
                                <!-- show logged in admin username -->
                                <div class="d-flex align-items-center">
                                    <span class="me-1"><?= swarm_icon('tabler:user-circle') ?></span>
                                    <span class="fw-semibold"><?= htmlspecialchars(SessionManager::get('username', 'Admin')) ?></span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= APP_BASE_URL ?>/auth/login"> <!-- Click Logout button will redirect to Login page -->
                                <button type="button" class="btn btn-outline-danger">
                                    <div class="d-flex align-items-center">
                                        <span class="me-1"><?= swarm_icon('lucide:log-out') ?></span>
                                        <span class="fw-semibold">Logout</span>
                                    </div>
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block admin-sidebar p-0">
                <ul class="nav flex-column px-2 mt-2">
                    <li class="nav-item">
                        <!-- active class set dynamically for each link -->
                        <a class="nav-link <?= isActive('dashboard') ?>" href="<?= APP_BASE_URL ?>/admin/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('user_management') ?>" href="<?= APP_BASE_URL ?>/admin/user_management">
                            <i class="bi bi-people me-2"></i>User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('upload') ?>" href="<?= APP_BASE_URL ?>/admin/upload">
                            <i class="bi bi-upload me-2"></i>File Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('item_management') ?>" href="<?= APP_BASE_URL ?>/admin/item_management">
                            <i class="bi bi-box-seam me-2"></i>Item Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('transactions') ?>" href="<?= APP_BASE_URL ?>/admin/transactions">
                            <i class="bi bi-receipt me-2"></i>Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('categories') ?>" href="<?= APP_BASE_URL ?>/admin/categories">
                            <i class="bi bi-tags me-2"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('reports') ?>" href="<?= APP_BASE_URL ?>/admin/reports">
                            <i class="bi bi-bar-chart me-2"></i>Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('system_notices') ?>" href="<?= APP_BASE_URL ?>/admin/system_notices">
                            <i class="bi bi-bell me-2"></i>System Notices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('activity_log') ?>" href="<?= APP_BASE_URL ?>/admin/activity_log">
                            <i class="bi bi-clock-history me-2"></i>Activity Log
                        </a>
                    </li>
                </ul>
                <hr class="text-secondary">
                <div class="px-2">
                    <a class="nav-link text-muted" href="<?= APP_BASE_URL ?>/">
                        &larr; Back to Store
                    </a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4 admin-content">