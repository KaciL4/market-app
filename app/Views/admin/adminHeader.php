<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- TODO: include your CSS files here -->
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

    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 px-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <h5>
                        Resale Management System
                    </h5>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">
                                <i class="bi bi-globe"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-brightness-high"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <button type="button" class="btn btn-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
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
                        <a class="nav-link active" href="<?= APP_BASE_URL ?>/admin/dashboard">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/user_management">
                            User Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/item_management">
                            Item Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/transactions">
                            Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/categories">
                            Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/reports">
                            Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/system_notices">
                            System Notices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= APP_BASE_URL ?>/admin/activity_log">
                            Activity Log
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
