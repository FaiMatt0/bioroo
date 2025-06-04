<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../config/config.php';
}

$pageTitle = 'Dettagli reso #' . $returnData['return_number'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/returns">I miei resi</a></li>
        <li class="breadcrumb-item active">Reso #<?= $returnData['return_number'] ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dettagli reso #<?= $returnData['return_number'] ?></h1>
    <div>
        <?php if (in_array($returnData['status'], ['requested', 'approved'])): ?>
            <a href="<?= BASE_URL ?>/returns/cancel/<?= $returnData['id'] ?>" 
               class="btn btn-outline-danger me-2"
               onclick="return confirm('Sei sicuro di voler annullare questo reso?')">
                <i class="fas fa-times me-2"></i> Annulla reso
            </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/returns" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Torna ai resi
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informazioni del reso</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Numero reso:</dt>
                            <dd class="col-sm-7"><strong><?= $returnData['return_number'] ?></strong></dd>
                            
                            <dt class="col-sm-5">Ordine originale:</dt>
                            <dd class="col-sm-7">
                                <a href="<?= BASE_URL ?>/orders/<?= $returnData['order_id'] ?>" class="text-decoration-none">
                                    #<?= $returnData['order_number'] ?>
                                </a>
                            </dd>
                            
                            <dt class="col-sm-5">Stato:</dt>
                            <dd class="col-sm-7">
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                switch ($returnData['status']) {
                                    case 'requested':
                                        $statusClass = 'bg-warning text-dark';
                                        $statusText = 'Richiesto';
                                        break;
                                    case 'approved':
                                        $statusClass = 'bg-info';
                                        $statusText = 'Approvato';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Rifiutato';
                                        break;
                                    case 'received':
                                        $statusClass = 'bg-primary';
                                        $statusText = 'Ricevuto';
                                        break;
                                    case 'refunded':
                                        $statusClass = 'bg-success';
                                        $statusText = 'Rimborsato';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-secondary';
                                        $statusText = 'Annullato';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        $statusText = 'Sconosciuto';
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                            </dd>
                            
                            <dt class="col-sm-5">Valore totale:</dt>
                            <dd class="col-sm-7"><strong><?= formatPrice($returnData['total_amount']) ?></strong></dd>
                        </dl>
                    </div>
                    
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Data richiesta:</dt>
                            <dd class="col-sm-7"><?= date('d/m/Y H:i', strtotime($returnData['requested_at'])) ?></dd>
                            
                            <?php if ($returnData['processed_at']): ?>
                                <dt class="col-sm-5">Data elaborazione:</dt>
                                <dd class="col-sm-7"><?= date('d/m/Y H:i', strtotime($returnData['processed_at'])) ?></dd>
                            <?php endif; ?>
                            
                            <?php if ($returnData['refunded_at']): ?>
                                <dt class="col-sm-5">Data rimborso:</dt>
                                <dd class="col-sm-7"><?= date('d/m/Y H:i', strtotime($returnData['refunded_at'])) ?></dd>
                            <?php endif; ?>
                            
                            <dt class="col-sm-5">Metodo rimborso:</dt>
                            <dd class="col-sm-7">
                                <?php
                                switch ($returnData['refund_method']) {
                                    case 'original_payment':
                                        echo 'Metodo di pagamento originale';
                                        break;
                                    case 'store_credit':
                                        echo 'Credito negozio';
                                        break;
                                    case 'bank_transfer':
                                        echo 'Bonifico bancario';
                                        break;
                                    default:
                                        echo 'Non specificato';
                                }
                                ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Motivo del reso</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Motivo principale:</dt>
                    <dd class="col-sm-9">
                        <?php
                        switch ($returnData['reason']) {
                            case 'defective':
                                echo 'Prodotto difettoso';
                                break;
                            case 'wrong_item':
                                echo 'Articolo sbagliato ricevuto';
                                break;
                            case 'not_as_described':
                                echo 'Non conforme alla descrizione';
                                break;
                            case 'changed_mind':
                                echo 'Ho cambiato idea';
                                break;
                            case 'damaged_shipping':
                                echo 'Danneggiato durante la spedizione';
                                break;
                            case 'other':
                                echo 'Altro';
                                break;
                            default:
                                echo 'Non specificato';
                        }
                        ?>
                    </dd>
                    
                    <dt class="col-sm-3">Descrizione:</dt>
                    <dd class="col-sm-9"><?= nl2br(htmlspecialchars($returnData['reason_description'])) ?></dd>
                </dl>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Prodotti nel reso</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th>Prezzo unitario</th>
                                <th>Quantità</th>
                                <th>Subtotale</th>
                                <?php if ($returnData['status'] === 'received' && isAdmin()): ?>
                                    <th>Condizioni</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($returnItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['product_image']): ?>
                                                <img src="<?= BASE_URL ?>/uploads/products/<?= $item['product_image'] ?>" 
                                                     alt="<?= $item['product_name'] ?>" 
                                                     class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= $item['product_name'] ?></strong>
                                                <br><small class="text-muted">Qtà originale: <?= $item['original_quantity'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= formatPrice($item['price']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><strong><?= formatPrice($item['price'] * $item['quantity']) ?></strong></td>
                                    <?php if ($returnData['status'] === 'received' && isAdmin()): ?>
                                        <td>
                                            <?php if ($item['condition_received']): ?>
                                                <span class="badge <?php 
                                                    switch($item['condition_received']) {
                                                        case 'new': echo 'bg-success'; break;
                                                        case 'good': echo 'bg-info'; break;
                                                        case 'fair': echo 'bg-warning'; break;
                                                        case 'poor': echo 'bg-danger'; break;
                                                        case 'damaged': echo 'bg-dark'; break;
                                                        default: echo 'bg-secondary';
                                                    }
                                                ?>">
                                                    <?php
                                                    switch($item['condition_received']) {
                                                        case 'new': echo 'Nuovo'; break;
                                                        case 'good': echo 'Buono'; break;
                                                        case 'fair': echo 'Discreto'; break;
                                                        case 'poor': echo 'Scarso'; break;
                                                        case 'damaged': echo 'Danneggiato'; break;
                                                        default: echo 'N/A';
                                                    }
                                                    ?>
                                                </span>
                                                <?php if ($item['notes']): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($item['notes']) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">In attesa di verifica</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Totale reso:</th>
                                <th><strong><?= formatPrice($returnData['total_amount']) ?></strong></th>
                                <?php if ($returnData['status'] === 'received' && isAdmin()): ?>
                                    <th></th>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Timeline del reso</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Reso richiesto</h6>
                            <p class="text-muted mb-0"><?= date('d/m/Y H:i', strtotime($returnData['requested_at'])) ?></p>
                        </div>
                    </div>
                    
                    <?php if ($returnData['processed_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker <?= $returnData['status'] === 'approved' ? 'bg-success' : 'bg-danger' ?>"></div>
                            <div class="timeline-content">
                                <h6><?= $returnData['status'] === 'approved' ? 'Reso approvato' : 'Reso rifiutato' ?></h6>
                                <p class="text-muted mb-0"><?= date('d/m/Y H:i', strtotime($returnData['processed_at'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($returnData['status'] === 'received'): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Prodotto ricevuto</h6>
                                <p class="text-muted mb-0">In verifica</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($returnData['refunded_at']): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Rimborso completato</h6>
                                <p class="text-muted mb-0"><?= date('d/m/Y H:i', strtotime($returnData['refunded_at'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($returnData['admin_notes']): ?>
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">Note dell'amministratore</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($returnData['admin_notes'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($returnData['status'] === 'approved'): ?>
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">Istruzioni di spedizione</h6>
                </div>
                <div class="card-body">
                    <p><strong>Il tuo reso è stato approvato!</strong></p>
                    <ol>
                        <li>Imballare i prodotti nelle condizioni originali</li>
                        <li>Includere questa pagina stampata</li>
                        <li>Spedire all'indirizzo:</li>
                    </ol>
                    <address>
                        <strong>Centro Resi Bioro</strong><br>
                        Via dei Resi, 123<br>
                        20100 Milano (MI)<br>
                        Italia
                    </address>
                    <p><small>Usa il servizio di corriere di tua scelta. Ti consigliamo di utilizzare un servizio con tracking.</small></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -25px;
    top: 25px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-content p {
    font-size: 12px;
}
</style>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
