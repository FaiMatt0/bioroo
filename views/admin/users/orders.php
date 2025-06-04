<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Ordini di ' . $user['first_name'] . ' ' . $user['last_name'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/users">Gestione Utenti</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/users/view/<?= $user['id'] ?>">Dettagli Utente</a></li>
        <li class="breadcrumb-item active">Ordini</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ordini di <?= $user['first_name'] . ' ' . $user['last_name'] ?></h1>
    <div>
        <a href="<?= BASE_URL ?>/admin/users/view/<?= $user['id'] ?>" class="btn btn-secondary me-2">
            <i class="fas fa-user me-2"></i> Dettagli Utente
        </a>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Lista Utenti
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h4>Totale Ordini</h4>
                <h2><?= count($orders) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-euro-sign fa-2x mb-2"></i>
                <h4>Totale Speso</h4>
                <h2><?= formatPrice(array_sum(array_column($orders, 'total_amount'))) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h4>Media Ordine</h4>
                <h2><?= count($orders) > 0 ? formatPrice(array_sum(array_column($orders, 'total_amount')) / count($orders)) : '€0,00' ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-2x mb-2"></i>
                <h4>Ultimo Ordine</h4>
                <h2><?= !empty($orders) ? date('d/m/Y', strtotime($orders[0]['created_at'])) : 'Mai' ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Lista Ordini</h5>
    </div>
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle fa-3x mb-3"></i>
                <h4>Nessun ordine trovato</h4>
                <p>Questo utente non ha ancora effettuato ordini.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="orders-table">
                    <thead>
                        <tr>
                            <th>ID Ordine</th>
                            <th>Data</th>
                            <th>Stato</th>
                            <th>Indirizzo di Spedizione</th>
                            <th>Totale</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['id'] ?></strong></td>
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
                                <td>
                                    <small>
                                        <?= $order['shipping_address'] ?><br>
                                        <?= $order['shipping_city'] ?> <?= $order['shipping_postal_code'] ?><br>
                                        <?= $order['shipping_country'] ?>
                                    </small>
                                </td>
                                <td><strong><?= formatPrice($order['total_amount']) ?></strong></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Visualizza
                                        </a>
                                        <a href="<?= BASE_URL ?>/admin/orders/invoice/<?= $order['id'] ?>" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-file-invoice"></i> Fattura
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza DataTable se ci sono ordini
    <?php if (!empty($orders)): ?>
    $('#orders-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Italian.json"
        },
        "order": [[0, "desc"]],
        "pageLength": 25,
        "responsive": true
    });
    <?php endif; ?>
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
