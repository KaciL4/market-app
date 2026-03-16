<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- TODO: include your CSS files here -->
</head>

<body>

    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    Resale Management System
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
