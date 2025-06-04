<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Checkout';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informazioni di spedizione</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['checkout_errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['checkout_errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['checkout_errors']); ?>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/order/process" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Indirizzo</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $user['address'] ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">Città</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?= $user['city'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">CAP</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= $user['postal_code'] ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">Paese</label>
                            <input type="text" class="form-control" id="country" name="country" value="<?= $user['country'] ?: 'Italia' ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Note per la consegna (opzionale)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">Procedi al pagamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Riepilogo ordine</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="mb-3">Prodotti</h6>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <span><?= $item['name'] ?></span>
                                <small class="d-block text-muted">Quantità: <?= $item['quantity'] ?></small>
                            </div>
                            <span><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotale</span>
                    <strong><?= formatPrice($total) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Spedizione</span>
                    <strong>Gratuita</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span>Totale</span>
                    <strong class="text-primary fs-4"><?= formatPrice($total) ?></strong>
                </div>
            </div>
        </div>
        
        <!-- Shipping methods -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Metodo di spedizione</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_method" id="standard" checked>
                    <label class="form-check-label" for="standard">
                        Standard (2-4 giorni lavorativi)
                        <span class="text-success float-end">Gratuita</span>
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="shipping_method" id="express">
                    <label class="form-check-label" for="express">
                        Express (1-2 giorni lavorativi)
                        <span class="float-end">€ 9,99</span>
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="shipping_method" id="same_day">
                    <label class="form-check-label" for="same_day">
                        Consegna in giornata
                        <span class="float-end">€ 14,99</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>