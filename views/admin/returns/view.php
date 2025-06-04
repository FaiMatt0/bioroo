<?php 
if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('error', 'Accesso negato. Devi essere un amministratore.');
    redirect('/auth/login');
}

if (!isset($return) || !$return) {
    redirect('/admin/returns');
}

$pageTitle = 'Dettagli Reso #' . $return['id'];
include VIEWS_PATH . '/layouts/header.php';

$statusLabels = [
    'requested' => 'Richiesto',
    'approved' => 'Approvato',
    'rejected' => 'Rifiutato',
    'received' => 'Ricevuto',
    'refunded' => 'Rimborsato'
];

$statusClass = [
    'requested' => 'warning',
    'approved' => 'info',
    'rejected' => 'danger',
    'received' => 'primary',
    'refunded' => 'success'
];
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/returns">Gestione Resi</a></li>
        <li class="breadcrumb-item active">Reso #<?= $return['id'] ?></li>
    </ol>
</nav>

<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>Dettagli Reso #<?= $return['id'] ?></h1>
                <p>Gestisci i dettagli del reso del cliente</p>
            </div>
            <div>
                <a href="/admin/returns" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Torna all'elenco
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Return Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informazioni Reso</h5>
                    <span class="badge bg-<?= $statusClass[$return['status']] ?> fs-6">
                        <?= $statusLabels[$return['status']] ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informazioni Cliente</h6>
                            <p class="mb-1"><strong>Nome:</strong> <?= htmlspecialchars($return['customer_name']) ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($return['customer_email']) ?></p>
                            <p class="mb-3"><strong>Ordine:</strong> 
                                <a href="/admin/orders/<?= $return['order_id'] ?>" class="text-decoration-none">
                                    #<?= $return['order_id'] ?>
                                </a>
                            </p>

                            <h6>Dettagli Rimborso</h6>
                            <p class="mb-1"><strong>Importo:</strong> €<?= number_format($return['refund_amount'], 2) ?></p>
                            <p class="mb-1"><strong>Metodo:</strong> <?= ucfirst($return['refund_method']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Date Importanti</h6>
                            <p class="mb-1"><strong>Richiesta:</strong> <?= date('d/m/Y H:i', strtotime($return['created_at'])) ?></p>
                            <?php if ($return['processed_at']): ?>
                                <p class="mb-1"><strong>Ultima modifica:</strong> <?= date('d/m/Y H:i', strtotime($return['processed_at'])) ?></p>
                            <?php endif; ?>
                            
                            <h6 class="mt-3">Motivo del Reso</h6>
                            <p class="mb-1"><?= htmlspecialchars($return['reason']) ?></p>
                        </div>
                    </div>

                    <?php if ($return['customer_notes']): ?>
                        <div class="mt-3">
                            <h6>Note del Cliente</h6>
                            <div class="alert alert-light">
                                <?= nl2br(htmlspecialchars($return['customer_notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($return['admin_notes']): ?>
                        <div class="mt-3">
                            <h6>Note Amministratore</h6>
                            <div class="alert alert-info">
                                <?= nl2br(htmlspecialchars($return['admin_notes'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Return Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Prodotti da Restituire</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Prodotto</th>
                                    <th>Quantità</th>
                                    <th>Prezzo Unitario</th>
                                    <th>Totale</th>
                                    <th>Condizione</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returnItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['product_image']): ?>
                                                    <img src="<?= htmlspecialchars($item['product_image']) ?>" 
                                                         alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                                         class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                                                    <?php if ($item['product_sku']): ?>
                                                        <br><small class="text-muted">SKU: <?= htmlspecialchars($item['product_sku']) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>€<?= number_format($item['price'], 2) ?></td>
                                        <td><strong>€<?= number_format($item['quantity'] * $item['price'], 2) ?></strong></td>
                                        <td>
                                            <?php if ($item['condition']): ?>
                                                <span class="badge bg-secondary"><?= ucfirst($item['condition']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Non specificato</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="3">Totale Rimborso</th>
                                    <th>€<?= number_format($return['refund_amount'], 2) ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cronologia Stato</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item active">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6>Reso Richiesto</h6>
                                <p class="text-muted mb-0"><?= date('d/m/Y H:i', strtotime($return['created_at'])) ?></p>
                                <small>Il cliente ha richiesto il reso</small>
                            </div>
                        </div>

                        <?php if (in_array($return['status'], ['approved', 'received', 'refunded'])): ?>
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6>Reso Approvato</h6>
                                    <p class="text-muted mb-0"><?= $return['processed_at'] ? date('d/m/Y H:i', strtotime($return['processed_at'])) : 'Data non disponibile' ?></p>
                                    <small>Il reso è stato approvato dall'amministratore</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($return['status'] === 'rejected'): ?>
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6>Reso Rifiutato</h6>
                                    <p class="text-muted mb-0"><?= $return['processed_at'] ? date('d/m/Y H:i', strtotime($return['processed_at'])) : 'Data non disponibile' ?></p>
                                    <small>Il reso è stato rifiutato</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($return['status'], ['received', 'refunded'])): ?>
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>Prodotti Ricevuti</h6>
                                    <p class="text-muted mb-0">Data da aggiornare</p>
                                    <small>I prodotti sono stati ricevuti e ispezionati</small>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($return['status'] === 'refunded'): ?>
                            <div class="timeline-item active">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>Rimborso Completato</h6>
                                    <p class="text-muted mb-0">Data da aggiornare</p>
                                    <small>Il rimborso è stato processato</small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Azioni</h5>
                </div>
                <div class="card-body">
                    <?php if ($return['status'] === 'requested'): ?>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" onclick="updateStatus('approved')">
                                <i class="fas fa-check"></i> Approva Reso
                            </button>
                            <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">
                                <i class="fas fa-times"></i> Rifiuta Reso
                            </button>
                        </div>
                    <?php elseif ($return['status'] === 'approved'): ?>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary" onclick="updateStatus('received')">
                                <i class="fas fa-box"></i> Segna come Ricevuto
                            </button>
                        </div>
                    <?php elseif ($return['status'] === 'received'): ?>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" onclick="updateStatus('refunded')">
                                <i class="fas fa-euro-sign"></i> Segna come Rimborsato
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Nessuna azione disponibile per questo stato.
                        </div>
                    <?php endif; ?>

                    <!-- Admin Notes Form -->
                    <div class="mt-4">
                        <h6>Note Amministratore</h6>
                        <form id="notesForm">
                            <div class="mb-3">
                                <textarea class="form-control" 
                                          id="adminNotes" 
                                          rows="4" 
                                          placeholder="Aggiungi note per questo reso..."><?= htmlspecialchars($return['admin_notes'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-save"></i> Salva Note
                            </button>
                        </form>
                    </div>

                    <!-- Quick Links -->
                    <div class="mt-4">
                        <h6>Collegamenti Rapidi</h6>
                        <div class="d-grid gap-1">
                            <a href="/admin/orders/<?= $return['order_id'] ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-shopping-cart"></i> Visualizza Ordine
                            </a>
                            <a href="/admin/users/<?= $return['user_id'] ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user"></i> Profilo Cliente
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-content p {
    font-size: 12px;
    margin-bottom: 2px;
}

.timeline-content small {
    font-size: 11px;
    color: #6c757d;
}
</style>

<script>
function updateStatus(newStatus) {
    const statusLabels = {
        'approved': 'approvare',
        'rejected': 'rifiutare',
        'received': 'segnare come ricevuto',
        'refunded': 'segnare come rimborsato'
    };

    if (confirm(`Sei sicuro di voler ${statusLabels[newStatus]} questo reso?`)) {
        fetch(`/admin/returns/<?= $return['id'] ?>/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore durante l\'aggiornamento dello stato: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante l\'aggiornamento dello stato');
        });
    }
}

document.getElementById('notesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const notes = document.getElementById('adminNotes').value;
    
    fetch(`/admin/returns/<?= $return['id'] ?>/notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Note salvate con successo');
        } else {
            alert('Errore durante il salvataggio delle note: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore durante il salvataggio delle note');
    });
});
</script>
