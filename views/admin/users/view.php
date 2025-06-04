<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Dettagli Utente - ' . $user['first_name'] . ' ' . $user['last_name'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/users">Gestione Utenti</a></li>
        <li class="breadcrumb-item active">Dettagli Utente</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dettagli Utente</h1>
    <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Torna alla lista
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informazioni Utente</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                    <h4 class="mt-3"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h4>
                    
                    <?php if ($user['is_admin']): ?>
                        <span class="badge bg-danger">Amministratore</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Cliente</span>
                    <?php endif; ?>
                </div>
                
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-envelope me-2"></i> Email</span>
                        <span><?= $user['email'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-phone me-2"></i> Telefono</span>
                        <span><?= $user['phone'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-map-marker-alt me-2"></i> Indirizzo</span>
                        <span><?= $user['address'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-city me-2"></i> Città</span>
                        <span><?= $user['city'] ?: 'Non specificata' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-mail-bulk me-2"></i> CAP</span>
                        <span><?= $user['postal_code'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-globe me-2"></i> Paese</span>
                        <span><?= $user['country'] ?: 'Non specificato' ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><i class="fas fa-calendar me-2"></i> Registrato</span>
                        <span><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                        <h4>Ordini Totali</h4>
                        <h2><?= $orderCount ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-euro-sign fa-2x mb-2"></i>
                        <h4>Totale Speso</h4>
                        <h2><?= formatPrice($totalSpent) ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <h4>Media Ordine</h4>
                        <h2><?= $orderCount > 0 ? formatPrice($totalSpent / $orderCount) : '€0,00' ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Ordini Recenti</h5>
                <a href="<?= BASE_URL ?>/admin/users/orders/<?= $user['id'] ?>" class="btn btn-light btn-sm">
                    Vedi tutti gli ordini
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($userOrders)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Questo utente non ha ancora effettuato ordini.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID Ordine</th>
                                    <th>Data</th>
                                    <th>Stato</th>
                                    <th>Totale</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($userOrders, 0, 5) as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <?php
                                            switch ($order['status']) {
                                                case 'pending':
                                                    echo '<span class="badge bg-warning text-dark">In attesa</span>';
                                                    break;
                                                case 'processing':
                                                    echo '<span class="badge bg-info">In elaborazione</span>';
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
                                            <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Visualizza
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
