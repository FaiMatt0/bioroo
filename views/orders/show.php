<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Dettagli Ordine #' . $order['id'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/orders">I miei ordini</a></li>
        <li class="breadcrumb-item active">Ordine #<?= $order['id'] ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ordine #<?= $order['id'] ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Dettagli ordine</h6>
                        <p>
                            <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?><br>
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
                            <br>
                            <strong>Pagamento:</strong> 
                            <?php
                            // Verifica se c'è un pagamento associato
                            if ($order['payment_id']) {
                                require_once MODELS_PATH . '/Payment.php';
                                $paymentModel = new Payment();
                                $payment = $paymentModel->getById($order['payment_id']);
                                
                                if ($payment) {
                                    $paymentMethodMapping = [
                                        'credit_card' => 'Carta di credito',
                                        'paypal' => 'PayPal',
                                        'bank_transfer' => 'Bonifico bancario'
                                    ];
                                    
                                    echo $paymentMethodMapping[$payment['payment_method']] ?? ucfirst($payment['payment_method']);
                                    echo ' - ';
                                    
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
                                } else {
                                    echo '<span class="badge bg-warning text-dark">In attesa</span>';
                                }
                            } else {
                                echo '<span class="badge bg-warning text-dark">In attesa</span>';
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
                                <th scope="col">Prodotto</th>
                                <th scope="col" class="text-center">Quantità</th>
                                <th scope="col" class="text-end">Prezzo</th>
                                <th scope="col" class="text-end">Totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="img-thumbnail me-3" width="50">
                                            <div>
                                                <a href="<?= BASE_URL ?>/products/<?= $item['product_id'] ?>" class="text-decoration-none"><?= $item['name'] ?></a>
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
                
                <?php if ($order['status'] === 'pending' && (!$order['payment_id'] || ($payment && $payment['status'] === 'pending'))): ?>
                    <div class="d-grid">
                        <a href="<?= BASE_URL ?>/payment" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i> Procedi al pagamento
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Stato dell'ordine</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Ordine effettuato</span>
                        <i class="fas fa-check-circle text-success"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Pagamento</span>
                        <?php if ($order['payment_id'] && $payment && $payment['status'] === 'completed'): ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>In elaborazione</span>
                        <?php if (in_array($order['status'], ['processing', 'shipped', 'delivered'])): ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Spedito</span>
                        <?php if (in_array($order['status'], ['shipped', 'delivered'])): ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php endif; ?>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Consegnato</span>
                        <?php if ($order['status'] === 'delivered'): ?>
                            <i class="fas fa-check-circle text-success"></i>
                        <?php else: ?>
                            <i class="fas fa-clock text-warning"></i>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
          <?php if ($order['status'] === 'shipped'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informazioni di spedizione</h5>
                </div>
                <div class="card-body">
                    <p>Il tuo ordine è stato spedito!</p>
                    <p><strong>Corriere:</strong> Corriere Esempio</p>
                    <p><strong>Numero tracciamento:</strong> TRK1234567890</p>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-truck me-2"></i> Traccia spedizione
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($order['status'] === 'delivered'): ?>
            <?php
            // Check if order can be returned (within 30 days)
            $deliveryDate = strtotime($order['updated_at']);
            $daysSinceDelivery = (time() - $deliveryDate) / (60 * 60 * 24);
            $canReturn = $daysSinceDelivery <= 30;
            
            // Check if return already exists
            require_once MODELS_PATH . '/ReturnModel.php';
            $returnModel = new ReturnModel();
            $existingReturns = $returnModel->getReturnsByOrder($order['id']);
            $hasActiveReturn = false;
            foreach ($existingReturns as $return) {
                if (!in_array($return['status'], ['rejected', 'cancelled'])) {
                    $hasActiveReturn = true;
                    break;
                }
            }
            ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Gestione Reso</h5>
                </div>
                <div class="card-body">
                    <?php if ($hasActiveReturn): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Hai già una richiesta di reso attiva per questo ordine.
                        </div>
                        <a href="<?= BASE_URL ?>/returns" class="btn btn-primary">
                            <i class="fas fa-undo me-2"></i> Visualizza Resi
                        </a>
                    <?php elseif ($canReturn): ?>
                        <p>Non sei soddisfatto del tuo ordine? Puoi richiedere un reso entro 30 giorni dalla consegna.</p>
                        <p><small class="text-muted">Giorni rimanenti per il reso: <?= max(0, 30 - floor($daysSinceDelivery)) ?></small></p>
                        <a href="<?= BASE_URL ?>/returns/create/<?= $order['id'] ?>" class="btn btn-warning">
                            <i class="fas fa-undo me-2"></i> Richiedi Reso
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Il periodo per richiedere un reso (30 giorni) è scaduto.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($existingReturns)): ?>
                        <div class="mt-3">
                            <h6>Storico Resi</h6>
                            <?php foreach ($existingReturns as $return): ?>
                                <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                                    <div>
                                        <strong>Reso #<?= $return['id'] ?></strong>
                                        <br><small class="text-muted"><?= date('d/m/Y', strtotime($return['created_at'])) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <?php
                                        $statusClass = [
                                            'requested' => 'warning',
                                            'approved' => 'info', 
                                            'rejected' => 'danger',
                                            'received' => 'primary',
                                            'refunded' => 'success',
                                            'cancelled' => 'secondary'
                                        ];
                                        $statusText = [
                                            'requested' => 'Richiesto',
                                            'approved' => 'Approvato',
                                            'rejected' => 'Rifiutato', 
                                            'received' => 'Ricevuto',
                                            'refunded' => 'Rimborsato',
                                            'cancelled' => 'Annullato'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusClass[$return['status']] ?> d-block mb-1">
                                            <?= $statusText[$return['status']] ?>
                                        </span>
                                        <a href="<?= BASE_URL ?>/returns/view/<?= $return['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            Dettagli
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hai bisogno di aiuto?</h5>
            </div>
            <div class="card-body">
                <p>Se hai domande riguardo al tuo ordine, contattaci:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i> supporto@marketplace.com</li>
                    <li><i class="fas fa-phone me-2"></i> +39 02 1234567</li>
                </ul>
                <a href="<?= BASE_URL ?>/contact" class="btn btn-outline-secondary">
                    <i class="fas fa-headset me-2"></i> Contattaci
                </a>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>