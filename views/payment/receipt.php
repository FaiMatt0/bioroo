// filepath: c:\xampp\htdocs\bioro\views\payment\receipt.php
<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Ricevuta di pagamento';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i> Pagamento completato</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    <h4 class="mt-3">Grazie per il tuo ordine!</h4>
                    <p>Il tuo pagamento è stato elaborato con successo.</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Dettagli dell'ordine</h5>
                        <p>
                            <strong>Numero ordine:</strong> #<?= $order['id'] ?><br>
                            <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?><br>
                            <strong>Stato:</strong> <span class="badge bg-success">Pagato</span><br>
                            <strong>Metodo di pagamento:</strong> <?= ucfirst(str_replace('_', ' ', $payment['payment_method'])) ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Dettagli della spedizione</h5>
                        <address>
                            <?= $order['shipping_address'] ?><br>
                            <?= $order['shipping_city'] ?>, <?= $order['shipping_postal_code'] ?><br>
                            <?= $order['shipping_country'] ?>
                        </address>
                    </div>
                </div>
                
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
                            <?php
                            require_once MODELS_PATH . '/OrderItem.php';
                            $orderItemModel = new OrderItem();
                            $items = $orderItemModel->getByOrder($order['id']);
                            
                            foreach ($items as $item):
                            ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
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
                
                <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i> Visualizza ordine
                    </a>
                    <a href="<?= BASE_URL ?>/products" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart me-2"></i> Continua lo shopping
                    </a>
                </div>
                
                <div class="mt-4 border-top pt-3">
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Stampa ricevuta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>