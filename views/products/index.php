<?php
$pageTitle = 'Prodotti';
include VIEWS_PATH . '/layouts/header.php';
?>

<h1 class="mb-4">Tutti i Prodotti</h1>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Categorie</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($categories as $category): ?>
                        <li class="list-group-item">
                            <a href="<?= BASE_URL ?>/products/category/<?= $category['id'] ?>" class="text-decoration-none">
                                <?= $category['name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="row">
            <?php if (empty($products)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <p>Nessun prodotto disponibile al momento.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                <p class="card-text text-muted"><?= $product['category_name'] ?></p>
                                <p class="card-text fw-bold"><?= formatPrice($product['price']) ?></p>
                                <p class="card-text small">Venditore: <?= $product['vendor_name'] ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= BASE_URL ?>/products/<?= $product['id'] ?>" class="btn btn-primary">Dettagli</a>
                                    <form action="<?= BASE_URL ?>/cart/add" method="POST">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>