<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - Marketplace' : 'Marketplace' ?></title>
    
    <!-- Use CDN versions of CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Local CSS file -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <!-- Inline critical CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #4361ee !important;
            padding: 1rem;
        }
        .navbar-brand, .navbar-dark .navbar-nav .nav-link {
            color: white !important;
        }
        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .card {
            margin-bottom: 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body data-page="<?= isset($pageType) ? $pageType : '' ?>">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">            <a class="navbar-brand" href="<?= BASE_URL ?>/">Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">                <!-- Fix navigation links -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/products">Prodotti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/pages/sustainability">Sostenibilità</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/pages/about">La Nostra Storia</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/pages/contact">Contatti</a>
                    </li>
                </ul>

                <!-- Update the search form -->
                <form class="d-flex me-2" action="<?= BASE_URL ?>/products/search" method="GET">
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Cerca prodotti..." required>
                    <button class="btn btn-outline-light" type="submit">Cerca</button>
                </form>

                <!-- Update user navigation -->
                <ul class="navbar-nav">                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <!-- Cart count code remains the same -->
                        </a>
                    </li>
                      <?php if (isLoggedIn()): ?>                        <!-- User is logged in -->
                        <li class="nav-item dropdown">                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?= isset($_SESSION['display_name']) ? $_SESSION['display_name'] : ($_SESSION['email'] ?? 'Utente') ?>                            </a>                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile">Profilo</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/orders">I miei ordini</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/returns">I miei resi</a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin">Pannello admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                            </ul>
                        </li>                    <?php else: ?>
                        <!-- User not logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/auth/login">
                                <i class="fas fa-user"></i> Accedi
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Container principale -->
    <div class="container my-4">
        <?php
        // Mostra messaggi flash
        $flash = getFlashMessage();
        if ($flash) {
            echo '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show" role="alert">';
            echo $flash['message'];
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
        }
        ?>