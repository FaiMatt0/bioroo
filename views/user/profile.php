<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Il mio profilo';
include VIEWS_PATH . '../layouts/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informazioni profilo</h5>
            </div>            <div class="card-body">
                <div class="text-center mb-4">
                    <h4 class="mt-3"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h4>
                    
                    <?php if (isAdmin()): ?>
                        <span class="badge bg-danger">Amministratore</span>
                    <?php endif; ?>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-envelope me-2"></i> Email</span>
                        <span><?= $user['email'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-phone me-2"></i> Telefono</span>
                        <span><?= $user['phone'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-map-marker-alt me-2"></i> Indirizzo</span>
                        <span><?= $user['address'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-city me-2"></i> Città</span>
                        <span><?= $user['city'] ?: 'Non specificata' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-globe me-2"></i> Paese</span>
                        <span><?= $user['country'] ?: 'Non specificato' ?></span>
                    </li>
                </ul>
                
                <div class="d-grid gap-2 mt-3">
                    <a href="<?= BASE_URL ?>/profile/edit" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Modifica profilo
                    </a>
                    <a href="<?= BASE_URL ?>/profile/change-password" class="btn btn-outline-primary">
                        <i class="fas fa-lock me-2"></i> Cambia password
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ordini recenti</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentOrders)): ?>
                    <div class="alert alert-info">
                        <p>Non hai ancora effettuato ordini.</p>
                        <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Vai allo shopping</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ordine #</th>
                                    <th>Data</th>
                                    <th>Stato</th>
                                    <th>Totale</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                        <td>
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
                                        </td>
                                        <td><?= formatPrice($order['total_amount']) ?></td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Dettagli
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-end">
                        <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i> Vedi tutti gli ordini
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>