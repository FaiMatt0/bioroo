<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../../config/config.php';
}

$pageTitle = 'Gestione Ordini';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item active">Gestione Ordini</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestione Ordini</h1>
    
    <div class="btn-group">
        <a href="<?= BASE_URL ?>/admin/orders" class="btn <?= !isset($_GET['status']) ? 'btn-primary' : 'btn-outline-primary' ?>">Tutti</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=pending" class="btn <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'btn-primary' : 'btn-outline-primary' ?>">In attesa</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=processing" class="btn <?= isset($_GET['status']) && $_GET['status'] == 'processing' ? 'btn-primary' : 'btn-outline-primary' ?>">In elaborazione</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=shipped" class="btn <?= isset($_GET['status']) && $_GET['status'] == 'shipped' ? 'btn-primary' : 'btn-outline-primary' ?>">Spediti</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=delivered" class="btn <?= isset($_GET['status']) && $_GET['status'] == 'delivered' ? 'btn-primary' : 'btn-outline-primary' ?>">Consegnati</a>
        <a href="<?= BASE_URL ?>/admin/orders?status=cancelled" class="btn <?= isset($_GET['status']) && $_GET['status'] == 'cancelled' ? 'btn-primary' : 'btn-outline-primary' ?>">Annullati</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="orders-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Totale</th>
                        <th>Stato</th>
                        <th>Pagamento</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once MODELS_PATH . '/Order.php';
                    require_once MODELS_PATH . '/Payment.php';
                    
                    $orderModel = new Order();
                    $paymentModel = new Payment();
                    
                    // Filtra per stato se specificato
                    $status = isset($_GET['status']) ? $_GET['status'] : null;
                    $orders = $status ? $orderModel->getByStatus($status) : $orderModel->getAll();
                    
                    foreach ($orders as $order): 
                        // Ottieni informazioni pagamento
                        $payment = $order['payment_id'] ? $paymentModel->getById($order['payment_id']) : null;                    ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= $order['customer_name'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
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
                                <?php if ($payment): ?>
                                    <?php
                                    switch ($payment['status']) {
                                        case 'pending':
                                            echo '<span class="badge bg-warning text-dark">In attesa</span>';
                                            break;
                                        case 'completed':
                                            echo '<span class="badge bg-success">Completato</span>';
                                            break;
                                        case 'failed':
                                            echo '<span class="badge bg-danger">Fallito</span>';
                                            break;
                                        case 'refunded':
                                            echo '<span class="badge bg-info">Rimborsato</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-secondary">Sconosciuto</span>';
                                    }
                                    ?>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">In attesa</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/admin/orders/<?= $order['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateStatusModal<?= $order['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal Aggiorna Stato -->
                                <div class="modal fade" id="updateStatusModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Aggiorna stato ordine #<?= $order['id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="<?= BASE_URL ?>/admin/orders/update-status/<?= $order['id'] ?>" method="POST">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="status<?= $order['id'] ?>" class="form-label">Stato</label>
                                                        <select class="form-select" id="status<?= $order['id'] ?>" name="status">
                                                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>In attesa</option>
                                                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>In elaborazione</option>
                                                            <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Spedito</option>
                                                            <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Consegnato</option>
                                                            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Annullato</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                                    <button type="submit" class="btn btn-primary">Aggiorna</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inizializza DataTable per la tabella ordini
        $('#orders-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Italian.json"
            },
            "order": [[0, "desc"]], // Ordina per ID in modo decrescente
            "pageLength": 10
        });
    });
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>