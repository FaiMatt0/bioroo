<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Pannello Amministrazione';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h3>Dashboard Amministratore</h3>
                <p>Benvenuto nel pannello di amministrazione del marketplace.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3"></i>
                <h4>Utenti</h4>
                <h2 class="display-4"><?= $userCount ?></h2>
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-light mt-3">Gestisci utenti</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <h4>Ordini</h4>
                <h2 class="display-4"><?= $orderCount ?></h2>
                <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-light mt-3">Gestisci ordini</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-box fa-3x mb-3"></i>
                <h4>Prodotti</h4>
                <h2 class="display-4"><?= $productCount ?></h2>
                <a href="<?= BASE_URL ?>/admin/products" class="btn btn-light mt-3">Gestisci prodotti</a>
            </div>
        </div>
    </div>
      <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                <h4>Incassi</h4>
                <h2 class="display-4"><?= formatPrice($totalRevenue) ?></h2>
                <a href="<?= BASE_URL ?>/admin/reports" class="btn btn-light mt-3">Visualizza report</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-undo fa-3x mb-3"></i>
                <h4>Resi</h4>
                <h2 class="display-4"><?= $returnCount ?></h2>
                <a href="<?= BASE_URL ?>/admin/returns" class="btn btn-light mt-3">Gestisci resi</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3"></i>
                <h4>Resi in Attesa</h4>
                <h2 class="display-4"><?= $pendingReturnCount ?></h2>
                <a href="<?= BASE_URL ?>/admin/returns?status=requested" class="btn btn-light mt-3">Gestisci attesa</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Resi recenti</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recentReturns)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Ordine</th>
                                    <th>Importo</th>
                                    <th>Stato</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentReturns as $return): ?>
                                    <tr>
                                        <td>#<?= $return['id'] ?></td>
                                        <td><?= htmlspecialchars($return['customer_name']) ?></td>
                                        <td>#<?= $return['order_id'] ?></td>
                                        <td>€<?= number_format($return['refund_amount'], 2) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'requested' => 'warning',
                                                'approved' => 'info',
                                                'rejected' => 'danger',
                                                'received' => 'primary',
                                                'refunded' => 'success'
                                            ];
                                            $statusLabels = [
                                                'requested' => 'Richiesto',
                                                'approved' => 'Approvato',
                                                'rejected' => 'Rifiutato',
                                                'received' => 'Ricevuto',
                                                'refunded' => 'Rimborsato'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $statusClass[$return['status']] ?>">
                                                <?= $statusLabels[$return['status']] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= BASE_URL ?>/admin/returns/<?= $return['id'] ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Nessun reso recente</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/admin/returns" class="btn btn-outline-primary">Vedi tutti i resi</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ordini recenti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Totale</th>
                                <th>Stato</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?><tr>
                                    <td>#<?= $order['id'] ?></td>
                                    <td><?= $order['customer_name'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                    <td><?= formatPrice($order['total_amount']) ?></td>
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
                                    <td>
                                        <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline-primary">Vedi tutti gli ordini</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Nuovi utenti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Data registrazione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($newUsers as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-primary">Gestisci utenti</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Azioni rapide</h5>
            </div>
            <div class="card-body">                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>/admin/products/create" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i> Aggiungi prodotto
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-info w-100">
                            <i class="fas fa-tags me-2"></i> Gestisci categorie
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>/admin/orders/pending" class="btn btn-warning w-100">
                            <i class="fas fa-clock me-2"></i> Ordini in attesa
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>/admin/returns?status=requested" class="btn btn-danger w-100">
                            <i class="fas fa-undo me-2"></i> Resi da approvare
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= BASE_URL ?>/admin/reports/export" class="btn btn-secondary w-100">
                            <i class="fas fa-file-export me-2"></i> Esporta report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>