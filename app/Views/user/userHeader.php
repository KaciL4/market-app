<?php

use App\Helpers\SessionManager;
?>
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
        .user-sidebar {
            min-height: 100vh;
            background-color: #374047;
        }

        .user-sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .user-sidebar .nav-link:hover,
        .user-sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }

        body {
            padding-top: 65px;
        }

        .user-content {
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

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= APP_BASE_URL ?>/dashboard">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('tabler:home') ?>
                                    <span class="fw-semibold">
                                        Home
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('tabler:list-search') ?>
                                    <span class="fw-semibold">
                                        Browse Items
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('tabler:upload') ?>
                                    <span class="fw-semibold">
                                        Upload Item
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('tabler:box') ?>
                                    <span class="fw-semibold">
                                        My Items
                                    </span>
                                </div>
                            </a>
                        </li>
                    </ul>
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
                                <?= swarm_icon('tabler:sun-high') ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('lucide:user') ?>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <div class="d-flex align-items-center gap-1">
                                    <?= swarm_icon('lucide:shopping-cart') ?>
                                    <span class="fw-semibold">
                                        Cart
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= APP_BASE_URL ?>/auth/logout"> <!-- Click Logout button will redirect to Login page -->
                                <button type="button" class="btn btn-outline-danger">
                                    <div class="d-flex align-items-center gap-1">
                                        <?= swarm_icon('lucide:log-out') ?>
                                        <span class="fw-semibold">
                                            Logout
                                        </span>
                                    </div>
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </header>

    <main class="user-content">
