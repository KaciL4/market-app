<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        .profile-content {
            background-color: #212d39be !important;
        }
    </style>
</head>

<body>

    <?php

    use App\Helpers\SessionManager;
    ?>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 px-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <h5>Resale Management System</h5>
                </a>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-globe"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-brightness-high"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_ADMIN_URL ?>/profile"> <!-- Click username will redirect to Admin Profile page -->
                                <!-- show logged in admin username -->
                                <i class="bi bi-person-circle"></i>
                                <?= htmlspecialchars(SessionManager::get('username', 'Admin')) ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= APP_BASE_URL ?>/auth/login"> <!-- Click Logout button will redirect to Login page -->
                                <button type="button" class="btn btn-outline-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Logout
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="profile-content p-2">
        <div class="card min-vh-100">