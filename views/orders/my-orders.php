<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'I miei ordini';
include VIEWS_PATH . '/layouts/header.php';
?>

<h1 class="mb-4">I miei ordini</h1>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">
        <p>Non hai ancora effettuato ordini.</p>
        <a href="<?= BASE_URL ?>/products" class="btn btn-primary">Vai allo shopping</a>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
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
                        <?php foreach ($orders as $order): ?>
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
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <a href="<?= BASE_URL ?>/payment" class="btn btn-sm btn-success">
                                            <i class="fas fa-credit-card"></i> Paga
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>