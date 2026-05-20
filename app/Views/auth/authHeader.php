<?php

use App\Helpers\SessionManager;

$page_title = $page_title ?? $title ?? 'Authentication';

$user = SessionManager::get('user');
$isLoggedIn = !empty($user);

global $translator;
$currentLocale = $translator->getLocale();
$availableLocales = $translator->getAvailableLocales();
?>

<!DOCTYPE html>
<html lang="<?= hs($currentLocale) ?>" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= hs($page_title) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary mb-2 px-1">

            <div class="container-fluid py-1">

                <a class="navbar-brand" href="<?= APP_BASE_URL ?>">
                    <div class="d-flex align-items-center gap-1">
                        <?= swarm_icon('tabler:building-store') ?>
                        <span class="fw-bold">Resale Management System</span>
                    </div>
                </a>

                <ul class="navbar-nav ms-auto align-items-center">

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

                                        <?= $locale === 'en' ? 'ᴇɴ English' : '🇫🇷 Français' ?>

                                    </a>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </li>

                </ul>

            </div>

        </nav>
    </header>

    <div class="mb-3">
        <?= App\Helpers\FlashMessage::render() ?>
    </div>
