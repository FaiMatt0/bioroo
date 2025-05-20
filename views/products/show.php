<?php
$pageTitle = $product['name'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/products">Prodotti</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/products/category/<?= $product['category_id'] ?>"><?= $product['category_name'] ?></a></li>
        <li class="breadcrumb-item active"><?= $product['name'] ?></li>
    </ol>
</nav>

<div class="row mb-4">
    <div class="col-md-5">
        <img src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?>" class="img-fluid rounded" alt="<?= $product['name'] ?>">
    </div>
    <div class="col-md-7">
        <h1><?= $product['name'] ?></h1>
        <p class="text-muted">Categoria: <?= $product['category_name'] ?></p>
        <p class="text-muted">Venditore: <?= $product['vendor_name'] ?></p>
        
        <?php
        // Mostra rating medio
        require_once MODELS_PATH . '/Review.php';
        $reviewModel = new Review();
        $avgRating = $reviewModel->getAverageRating($product['id']);
        $reviews = $reviewModel->getByProduct($product['id']);
        $reviewCount = count($reviews);
        ?>
        
        <div class="mb-3">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <?php if ($i <= $avgRating): ?>
                    <i class="fas fa-star text-warning"></i>
                <?php elseif ($i <= $avgRating + 0.5): ?>
                    <i class="fas fa-star-half-alt text-warning"></i>
                <?php else: ?>
                    <i class="far fa-star text-warning"></i>
                <?php endif; ?>
            <?php endfor; ?>
            <span class="ms-2"><?= number_format($avgRating, 1) ?> (<?= $reviewCount ?> recensioni)</span>
        </div>
        
        <h2 class="fw-bold fs-1 text-primary mb-3"><?= formatPrice($product['price']) ?></h2>
        
        <p><?= nl2br($product['description']) ?></p>
        
        <div class="d-flex align-items-center mb-3">
            <span class="me-3"><?= $product['stock_quantity'] > 0 ? "<span class='text-success'>Disponibile</span>" : "<span class='text-danger'>Non disponibile</span>" ?></span>
            <span><?= $product['stock_quantity'] ?> in magazzino</span>
        </div>
        
        <?php if ($product['stock_quantity'] > 0): ?>
            <form action="<?= BASE_URL ?>/cart/add" method="POST" class="d-flex align-items-center mb-4">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div class="input-group me-3" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                    <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                    <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cart-plus me-2"></i> Aggiungi al carrello
                </button>
            </form>
        <?php endif; ?>
        
        <div class="d-flex justify-content-start mb-3">
            <button class="btn btn-outline-primary me-2">
                <i class="far fa-heart me-1"></i> Aggiungi ai preferiti
            </button>
            <button class="btn btn-outline-secondary">
                <i class="fas fa-share-alt me-1"></i> Condividi
            </button>
        </div>
    </div>
</div>

<!-- Tabs per descrizione, specifiche e recensioni -->
<ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Descrizione</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Recensioni (<?= $reviewCount ?>)</button>
    </li>
</ul>

<div class="tab-content" id="productTabsContent">
    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
        <div class="card">
            <div class="card-body">
                <h4>Dettagli prodotto</h4>
                <p><?= nl2br($product['description']) ?></p>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
        <div class="card">
            <div class="card-body">
                <h4>Recensioni cliente</h4>
                
                <?php if (empty($reviews)): ?>
                    <p class="text-muted">Nessuna recensione ancora per questo prodotto.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong><?= $review['username'] ?></strong>
                                    <span class="text-muted ms-2"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="mb-0"><?= nl2br($review['comment']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if (isLoggedIn()): ?>
                    <h5 class="mt-4">Lascia una recensione</h5>
                    <form action="<?= BASE_URL ?>/reviews/add" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Valutazione</label>
                            <div class="rating-stars mb-3">
                                <div class="d-flex">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="me-2">
                                            <input type="radio" id="rating-<?= $i ?>" name="rating" value="<?= $i ?>" class="d-none" <?= $i == 5 ? 'checked' : '' ?>>
                                            <label for="rating-<?= $i ?>" class="fa-star-label">
                                                <i class="far fa-star rating-star fs-3"></i>
                                            </label>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Commento</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Invia recensione</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info mt-4">
                        <p>Devi <a href="<?= BASE_URL ?>/login">accedere</a> per lasciare una recensione.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Prodotti correlati -->
<?php if (!empty($relatedProducts)): ?>
    <div class="mt-5">
        <h3 class="mb-4">Prodotti correlati</h3>
        <div class="row">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <?php if ($relatedProduct['id'] != $product['id']): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="<?= BASE_URL ?>/uploads/products/<?= $relatedProduct['image'] ?>" class="card-img-top" alt="<?= $relatedProduct['name'] ?>" style="height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= $relatedProduct['name'] ?></h5>
                                <p class="card-text fw-bold"><?= formatPrice($relatedProduct['price']) ?></p>
                                <a href="<?= BASE_URL ?>/products/<?= $relatedProduct['id'] ?>" class="btn btn-outline-primary btn-sm">Dettagli</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
    // Script per aggiornare la quantità
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        
        decreaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            let maxValue = parseInt(quantityInput.getAttribute('max'));
            if (value < maxValue) {
                quantityInput.value = value + 1;
            }
        });
        
        // Rating stars
        const ratingLabels = document.querySelectorAll('.fa-star-label');
        ratingLabels.forEach(label => {
            label.addEventListener('mouseover', function() {
                const ratingValue = this.getAttribute('for').split('-')[1];
                
                ratingLabels.forEach((l, index) => {
                    const star = l.querySelector('.rating-star');
                    if (index < ratingValue) {
                        star.classList.remove('far');
                        star.classList.add('fas', 'text-warning');
                    } else {
                        star.classList.remove('fas', 'text-warning');
                        star.classList.add('far');
                    }
                });
            });
            
            label.addEventListener('click', function() {
                const ratingValue = this.getAttribute('for').split('-')[1];
                
                ratingLabels.forEach((l, index) => {
                    const star = l.querySelector('.rating-star');
                    if (index < ratingValue) {
                        star.classList.remove('far');
                        star.classList.add('fas', 'text-warning');
                    } else {
                        star.classList.remove('fas', 'text-warning');
                        star.classList.add('far');
                    }
                });
                
                document.getElementById(`rating-${ratingValue}`).checked = true;
            });
        });
        
        const ratingContainer = document.querySelector('.rating-stars');
        ratingContainer.addEventListener('mouseout', function() {
            ratingLabels.forEach((l, index) => {
                const star = l.querySelector('.rating-star');
                const input = document.getElementById(`rating-${index + 1}`);
                
                if (input.checked) {
                    star.classList.remove('far');
                    star.classList.add('fas', 'text-warning');
                } else {
                    star.classList.remove('fas', 'text-warning');
                    star.classList.add('far');
                }
            });
        });
    });
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>