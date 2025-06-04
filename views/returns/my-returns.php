<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../config/config.php';
}

$pageTitle = 'I miei resi';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/orders">I miei ordini</a></li>
        <li class="breadcrumb-item active">I miei resi</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>I miei resi</h1>
    <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i> Torna agli ordini
    </a>
</div>

<?php if (empty($returns)): ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-undo fa-4x text-muted mb-3"></i>
            <h4>Nessun reso trovato</h4>
            <p class="text-muted">Non hai ancora richiesto nessun reso.</p>
            <a href="<?= BASE_URL ?>/orders" class="btn btn-primary">
                <i class="fas fa-shopping-cart me-2"></i> Visualizza i tuoi ordini
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($returns as $return): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Reso #<?= $return['return_number'] ?></h6>
                        <?php
                        $statusClass = '';
                        $statusText = '';
                        switch ($return['status']) {
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
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Ordine:</small>
                                <div><strong>#<?= $return['order_number'] ?></strong></div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Articoli:</small>
                                <div><strong><?= $return['items_count'] ?></strong></div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Motivo:</small>
                                <div>
                                    <?php
                                    switch ($return['reason']) {
                                        case 'defective':
                                            echo 'Prodotto difettoso';
                                            break;
                                        case 'wrong_item':
                                            echo 'Articolo sbagliato';
                                            break;
                                        case 'not_as_described':
                                            echo 'Non conforme';
                                            break;
                                        case 'changed_mind':
                                            echo 'Ripensamento';
                                            break;
                                        case 'damaged_shipping':
                                            echo 'Danneggiato';
                                            break;
                                        case 'other':
                                            echo 'Altro';
                                            break;
                                        default:
                                            echo 'Non specificato';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Valore:</small>
                                <div><strong><?= formatPrice($return['total_amount']) ?></strong></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Data richiesta:</small>
                            <div><?= date('d/m/Y H:i', strtotime($return['requested_at'])) ?></div>
                        </div>
                        
                        <?php if ($return['processed_at']): ?>
                            <div class="mb-3">
                                <small class="text-muted">Data elaborazione:</small>
                                <div><?= date('d/m/Y H:i', strtotime($return['processed_at'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div class="d-grid gap-2">
                            <a href="<?= BASE_URL ?>/returns/view/<?= $return['id'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye me-2"></i> Visualizza dettagli
                            </a>
                            <?php if (in_array($return['status'], ['requested', 'approved'])): ?>
                                <a href="<?= BASE_URL ?>/returns/cancel/<?= $return['id'] ?>" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Sei sicuro di voler annullare questo reso?')">
                                    <i class="fas fa-times me-2"></i> Annulla reso
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="mt-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-info-circle me-2"></i> Informazioni sui resi
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>Politica di reso:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Resi entro 30 giorni dalla consegna</li>
                        <li><i class="fas fa-check text-success me-2"></i> Prodotti in condizioni originali</li>
                        <li><i class="fas fa-check text-success me-2"></i> Rimborso completo approvato</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Stati del reso:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-warning text-dark me-2">Richiesto</span> In attesa di approvazione</li>
                        <li><span class="badge bg-info me-2">Approvato</span> Puoi spedire il prodotto</li>
                        <li><span class="badge bg-primary me-2">Ricevuto</span> Prodotto ricevuto e in verifica</li>
                        <li><span class="badge bg-success me-2">Rimborsato</span> Rimborso completato</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
