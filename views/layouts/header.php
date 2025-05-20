<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - Marketplace' : 'Marketplace' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body data-page="<?= isset($pageType) ? $pageType : '' ?>">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/">Marketplace</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/products">Prodotti</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Categorie
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            // Carica le categorie
                            require_once MODELS_PATH . '/Category.php';
                            $categoryModel = new Category();
                            $categories = $categoryModel->getAll();
                            
                            foreach ($categories as $category) {
                                echo '<li><a class="dropdown-item" href="' . BASE_URL . '/products/category/' . $category['id'] . '">' . $category['name'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/about">Chi siamo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/sustainability">Sostenibilità</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/contact">Contatti</a>
                    </li>
                </ul>
                
                <!-- Form di ricerca -->
                <form class="d-flex me-3" action="<?= BASE_URL ?>/products/search" method="GET">
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Cerca prodotti..." required>
                    <button class="btn btn-outline-light" type="submit">Cerca</button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <!-- Utente loggato -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/cart">
                                <i class="fas fa-shopping-cart"></i> Carrello
                                <?php
                                // Mostra conteggio elementi carrello
                                require_once MODELS_PATH . '/Cart.php';
                                $cartModel = new Cart();
                                $cartCount = $cartModel->countItems($_SESSION['user_id']);
                                
                                if ($cartCount > 0) {
                                    echo '<span class="badge bg-danger">' . $cartCount . '</span>';
                                }
                                ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= $_SESSION['username'] ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile">Profilo</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/orders">I miei ordini</a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin">Pannello admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Utente non loggato -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/register">Registrati</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Container principale -->
    <div class="container mb-4">
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