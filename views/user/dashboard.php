<?php
$pageTitle = 'Dashboard';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h3>Benvenuto, <?= $user['first_name'] ?>!</h3>
                <p>Qui puoi gestire il tuo account e visualizzare i tuoi ordini.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-bag fa-3x text-primary mb-3"></i>
                <h4>I tuoi ordini</h4>
                <h2 class="display-4"><?= $orderCount ?></h2>
                <a href="<?= BASE_URL ?>/orders" class="btn btn-primary mt-3">Visualizza ordini</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-user fa-3x text-primary mb-3"></i>
                <h4>Il tuo profilo</h4>
                <p>Gestisci le tue informazioni personali</p>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-primary mt-3">Vai al profilo</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                <h4>Lista desideri</h4>
                <p>Salva i prodotti che ti interessano</p>
                <a href="<?= BASE_URL ?>/wishlist" class="btn btn-primary mt-3">Vai alla lista</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ultimi ordini</h5>
            </div>
            <div class="card-body">
                <?php
                $recentOrders = $orderModel->getByUser($_SESSION['user_id'], 3);
                
                if (empty($recentOrders)): ?>
                    <div class="alert alert-info">
                        Non hai ancora effettuato ordini.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($recentOrders as $order): ?>
                            <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Ordine #<?= $order['id'] ?></h6>
                                    <small><?= date('d/m/Y', strtotime($order['created_at'])) ?></small>
                                </div>
                                <p class="mb-1">Totale: <?= formatPrice($order['total_amount']) ?></p>
                                <small>
                                    <?php
                                    switch ($order['status']) {
                                        case 'pending':
                                            echo '<span class="badge bg-warning text-dark">In attesa</span>';
                                            break;
                                        case 'processing':
                                            echo '<span class="badge bg-info text-dark">In elaborazione</span>';
                                            break;
                                        case 'shipped':
                                            echo '<span class="badge bg-primary">Spedito</span>';
                                            break;
                                        case 'delivered':
                                            echo '<span class="badge bg-success">Consegnato</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span class="badge bg-danger">Annullato</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-secondary">Sconosciuto</span>';
                                    }
                                    ?>
                                </small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/orders" class="btn btn-sm btn-outline-primary">Vedi tutti</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Supporto clienti</h5>
            </div>
            <div class="card-body">
                <p>Hai bisogno di aiuto? Il nostro team è disponibile per assisterti.</p>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-envelope me-2 text-primary"></i> supporto@marketplace.com
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-phone me-2 text-primary"></i> +39 02 1234567
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock me-2 text-primary"></i> Lun-Ven: 9:00 - 18:00
                    </li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i> Ricorda di avere a portata di mano il numero del tuo ordine quando ci contatti per assistenza.
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/contact" class="btn btn-sm btn-outline-primary">Contattaci</a>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>