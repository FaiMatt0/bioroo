<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Carrello';
include VIEWS_PATH . '/layouts/header.php';
?>

<h1 class="mb-4">Il tuo carrello</h1>

<?php if (empty($cartItems)): ?>
    <div class="alert alert-info">
        <p>Il tuo carrello è vuoto.</p>
        <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Continua lo shopping</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" width="100">Prodotto</th>
                                <th scope="col">Descrizione</th>
                                <th scope="col" class="text-center" width="120">Prezzo</th>
                                <th scope="col" class="text-center" width="130">Quantità</th>
                                <th scope="col" class="text-end" width="120">Totale</th>
                                <th scope="col" class="text-end" width="80">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="img-thumbnail" width="80">
                                    </td>
                                    <td>
                                        <h5><a href="<?= BASE_URL ?>/products/<?= $item['product_id'] ?>" class="text-decoration-none"><?= $item['name'] ?></a></h5>
                                    </td>
                                    <td class="text-center"><?= formatPrice($item['price']) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/cart/update" method="POST" class="d-flex justify-content-center">
                                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.closest('form').querySelector('input[name=quantity]').stepDown(); this.closest('form').submit();">-</button>
                                                <input type="number" class="form-control form-control-sm text-center" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="99" onchange="this.closest('form').submit();">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="this.closest('form').querySelector('input[name=quantity]').stepUp(); this.closest('form').submit();">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="text-end"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                                    <td class="text-end">
                                        <form action="<?= BASE_URL ?>/cart/remove" method="POST">
                                            <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/products" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Continua lo shopping
                </a>
                <form action="<?= BASE_URL ?>/cart/clear" method="POST">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash me-2"></i> Svuota carrello
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Riepilogo ordine</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotale</span>
                        <strong><?= formatPrice($total) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Spedizione</span>
                        <strong>Gratuita</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Totale</span>
                        <strong class="text-primary fs-4"><?= formatPrice($total) ?></strong>
                    </div>
                    
                    <div class="d-grid">
                        <a href="<?= BASE_URL ?>/checkout" class="btn btn-primary py-2">
                            <i class="fas fa-credit-card me-2"></i> Procedi al checkout
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Coupon code (optional) -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Codice sconto</h5>
                    <form action="<?= BASE_URL ?>/cart/coupon" method="POST">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="coupon_code" placeholder="Inserisci codice">
                            <button class="btn btn-outline-secondary" type="submit">Applica</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>