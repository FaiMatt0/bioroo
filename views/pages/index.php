<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Home';
include VIEWS_PATH . '/layouts/header.php';
?>

<!-- Hero Section -->
<div class="hero-section mb-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">            <div class="carousel-item active">
                <img src="<?= BASE_URL ?>/assets/images/hero/slide1.jpg" class="d-block w-100" alt="Benvenuti nel nostro marketplace">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Benvenuti nel nostro Marketplace</h1>
                    <p>Scopri prodotti di qualità selezionati per te</p>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary btn-lg">Esplora prodotti</a>
                </div>
                <!-- Caption per dispositivi mobili -->
                <div class="carousel-caption-mobile d-md-none">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Esplora prodotti</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>/assets/images/hero/slide2.jpg" class="d-block w-100" alt="Nuovi arrivi">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Nuovi arrivi</h1>
                    <p>Scopri le nostre ultime novità</p>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary btn-lg">Scopri di più</a>
                </div>
                <!-- Caption per dispositivi mobili -->
                <div class="carousel-caption-mobile d-md-none">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Scopri di più</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="<?= BASE_URL ?>/assets/images/hero/slide3.jpg" class="d-block w-100" alt="Offerte speciali">
                <div class="carousel-caption d-none d-md-block">
                    <h1>Offerte speciali</h1>
                    <p>Approfitta delle nostre promozioni</p>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary btn-lg">Scopri le offerte</a>
                </div>
                <!-- Caption per dispositivi mobili -->
                <div class="carousel-caption-mobile d-md-none">
                    <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Scopri le offerte</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Precedente</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Successivo</span>
        </button>
    </div>
</div>

<!-- Categorie in evidenza -->
<section class="mb-5">
    <div class="container">
        <h2 class="text-center mb-4">Esplora le nostre categorie</h2>
        <div class="row">
            <?php foreach ($mainCategories as $category): ?>
                <div class="col-md-4 mb-4">
                    <div class="card category-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-tag fa-3x mb-3 text-primary"></i>
                            <h3><?= $category['name'] ?></h3>
                            <p><?= $category['description'] ?></p>
                            <a href="<?= BASE_URL ?>/products/category/<?= $category['id'] ?>" class="btn btn-outline-primary">Scopri</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Prodotti in evidenza -->
<section class="mb-5 bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Prodotti in evidenza</h2>
        <div class="row">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 product-card">
                        <img src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product['name'] ?></h5>
                            <p class="card-text text-muted"><?= $product['category_name'] ?></p>
                            <p class="card-text fw-bold"><?= formatPrice($product['price']) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>/products/<?= $product['id'] ?>" class="btn btn-sm btn-primary">Dettagli</a>
                                <form action="<?= BASE_URL ?>/cart/add" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Vedi tutti i prodotti</a>
        </div>
    </div>
</section>

<!-- Vantaggi -->
<section class="mb-5">
    <div class="container">
        <h2 class="text-center mb-4">Perché scegliere noi</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-truck fa-3x text-primary mb-3"></i>
                    <h3>Spedizione gratuita</h3>
                    <p>Su tutti gli ordini superiori a €50</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-check-circle fa-3x text-primary mb-3"></i>
                    <h3>Qualità garantita</h3>
                    <p>Tutti i nostri prodotti sono selezionati con cura</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h3>Assistenza clienti</h3>
                    <p>Supporto disponibile 7 giorni su 7</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h2>Iscriviti alla nostra newsletter</h2>
                <p>Ricevi aggiornamenti sui nuovi prodotti e offerte speciali</p>
            </div>
            <div class="col-md-6">
                <form action="<?= BASE_URL ?>/newsletter/subscribe" method="POST" class="d-flex">
                    <input type="email" class="form-control me-2" placeholder="La tua email" required>
                    <button type="submit" class="btn btn-light">Iscriviti</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>