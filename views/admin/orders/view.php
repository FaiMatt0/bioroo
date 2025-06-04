<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../../config/config.php';
}

$pageTitle = 'Dettagli Ordine';
include VIEWS_PATH . '/layouts/header.php';

// Ottieni l'ordine
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/OrderItem.php';
require_once MODELS_PATH . '/Payment.php';

$orderModel = new Order();
$orderItemModel = new OrderItem();
$paymentModel = new Payment();

$orderId = isset($id) ? $id : (isset($_GET['id']) ? $_GET['id'] : 0);
$order = $orderModel->getById($orderId);

if (!$order) {
    setFlashMessage('error', 'Ordine non trovato.');
    redirect('/admin/orders');
}

$orderItems = $orderItemModel->getByOrder($orderId);
$payment = $order['payment_id'] ? $paymentModel->getById($order['payment_id']) : null;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/orders">Gestione Ordini</a></li>
        <li class="breadcrumb-item active">Ordine #<?= $order['id'] ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ordine #<?= $order['id'] ?></h1>
    
    <div class="btn-group">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
            <i class="fas fa-edit me-2"></i> Aggiorna stato
        </button>
        <a href="<?= BASE_URL ?>/admin/orders/invoice/<?= $order['id'] ?>" class="btn btn-primary" target="_blank">
            <i class="fas fa-file-invoice me-2"></i> Fattura
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Dettagli ordine</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informazioni ordine</h6>                        <p>
                            <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?><br>
                            <strong>Cliente:</strong> <?= $order['customer_name'] ?> (<?= $order['email'] ?>)<br>
                            <strong>Stato:</strong> 
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
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Indirizzo di spedizione</h6>
                        <address>
                            <?= $order['shipping_address'] ?><br>
                            <?= $order['shipping_city'] ?>, <?= $order['shipping_postal_code'] ?><br>
                            <?= $order['shipping_country'] ?>
                        </address>
                    </div>
                </div>
                
                <h6>Prodotti ordinati</h6>
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th class="text-center">Quantità</th>
                                <th class="text-end">Prezzo</th>
                                <th class="text-end">Totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="img-thumbnail me-3" width="50">
                                            <div>
                                                <a href="<?= BASE_URL ?>/products/<?= $item['product_id'] ?>" target="_blank"><?= $item['name'] ?></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end"><?= formatPrice($item['price']) ?></td>
                                    <td class="text-end"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotale</strong></td>
                                <td class="text-end"><?= formatPrice($order['total_amount']) ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Spedizione</strong></td>
                                <td class="text-end">Gratuita</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Totale</strong></td>
                                <td class="text-end"><strong><?= formatPrice($order['total_amount']) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pagamento</h5>
            </div>
            <div class="card-body">
                <?php if ($payment): ?>
                    <p>
                        <strong>Metodo:</strong>
                        <?php
                        switch ($payment['payment_method']) {
                            case 'credit_card':
                                echo 'Carta di credito';
                                break;
                            case 'paypal':
                                echo 'PayPal';
                                break;
                            case 'bank_transfer':
                                echo 'Bonifico bancario';
                                break;
                            default:
                                echo ucfirst($payment['payment_method']);
                        }
                        ?>
                    </p>
                    <p>
                        <strong>Stato:</strong>
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
                    </p>
                    <p><strong>ID Transazione:</strong> <?= $payment['transaction_id'] ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($payment['created_at'])) ?></p>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p>Nessun pagamento registrato per questo ordine.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Storico ordine</h5>
            </div>
            <div class="card-body">
                <ul class="timeline">
                    <li class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">Ordine creato</h6>
                            <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                        </div>
                    </li>
                    
                    <?php if ($payment): ?>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pagamento <?= $payment['status'] == 'completed' ? 'completato' : 'registrato' ?></h6>
                                <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($payment['created_at'])) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] == 'processing'): ?>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Ordine in elaborazione</h6>
                                <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] == 'shipped'): ?>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Ordine spedito</h6>
                                <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] == 'delivered'): ?>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Ordine consegnato</h6>
                                <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] == 'cancelled'): ?>
                        <li class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Ordine annullato</h6>
                                <p class="timeline-date"><?= date('d/m/Y H:i', strtotime($order['updated_at'])) ?></p>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aggiorna Stato -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aggiorna stato ordine #<?= $order['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/admin/orders/update-status/<?= $order['id'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Stato</label>
                        <select class="form-select" id="status" name="status">
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

<style>
.timeline {
    position: relative;
    padding-left: 32px;
    margin: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-marker {
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #4361ee;
    background-color: #fff;
    left: -24px;
    top: 4px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -17px;
    top: 24px;
    height: calc(100% - 4px);
    width: 2px;
    background-color: #e9ecef;
}

.timeline-title {
    margin-bottom: 4px;
}

.timeline-date {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0;
}
</style>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>