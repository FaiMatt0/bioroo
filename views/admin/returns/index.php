<?php 
if (!isLoggedIn() || !isAdmin()) {
    setFlashMessage('error', 'Accesso negato. Devi essere un amministratore.');
    redirect('/auth/login');
}

$pageTitle = 'Gestione Resi';
include VIEWS_PATH . '/layouts/header.php';

// Get return statistics
$totalReturns = $returnModel->countReturns();
$pendingReturns = $returnModel->countByStatus('requested');
$approvedReturns = $returnModel->countByStatus('approved');
$processingReturns = $returnModel->countByStatus('received');
$completedReturns = $returnModel->countByStatus('refunded');

// Get all returns with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$status = isset($_GET['status']) ? $_GET['status'] : '';
$returns = $returnModel->getAllWithDetails($status, $limit, $offset);
$totalCount = $returnModel->countReturns($status);
$totalPages = ceil($totalCount / $limit);
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item active">Gestione Resi</li>
    </ol>
</nav>

<div class="admin-content">
    <div class="content-header">
        <h1>Gestione Resi</h1>
        <p>Gestisci tutte le richieste di reso dei clienti</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?= $totalReturns ?></h3>
                        <p>Resi Totali</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?= $pendingReturns ?></h3>
                        <p>In Attesa</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?= $approvedReturns ?></h3>
                        <p>Approvati</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-euro-sign text-info"></i>
                    </div>
                    <div class="stats-content">
                        <h3><?= $completedReturns ?></h3>
                        <p>Rimborsati</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Filtra per Stato</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tutti gli stati</option>
                        <option value="requested" <?= $status === 'requested' ? 'selected' : '' ?>>Richiesto</option>
                        <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approvato</option>
                        <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rifiutato</option>
                        <option value="received" <?= $status === 'received' ? 'selected' : '' ?>>Ricevuto</option>
                        <option value="refunded" <?= $status === 'refunded' ? 'selected' : '' ?>>Rimborsato</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtra</button>
                    <a href="/admin/returns" class="btn btn-outline-secondary ms-2">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Elenco Resi</h5>
            <small class="text-muted"><?= $totalCount ?> resi trovati</small>
        </div>
        <div class="card-body">
            <?php if (empty($returns)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>Nessun reso trovato</h5>
                    <p class="text-muted">Non ci sono resi che corrispondono ai criteri di ricerca.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Reso</th>
                                <th>Cliente</th>
                                <th>Ordine</th>
                                <th>Prodotti</th>
                                <th>Importo</th>
                                <th>Stato</th>
                                <th>Data Richiesta</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($returns as $return): ?>
                                <tr>
                                    <td>
                                        <strong>#<?= $return['id'] ?></strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?= htmlspecialchars($return['customer_name']) ?></strong><br>
                                            <small class="text-muted"><?= htmlspecialchars($return['customer_email']) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/<?= $return['order_id'] ?>" class="text-decoration-none">
                                            #<?= $return['order_id'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $return['total_items'] ?> prodotto<?= $return['total_items'] > 1 ? 'i' : '' ?>
                                    </td>
                                    <td>
                                        <strong>€<?= number_format($return['refund_amount'], 2) ?></strong>
                                    </td>
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
                                        <?= date('d/m/Y H:i', strtotime($return['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/admin/returns/<?= $return['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Visualizza dettagli">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($return['status'] === 'requested'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        onclick="updateReturnStatus(<?= $return['id'] ?>, 'approved')"
                                                        title="Approva">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        onclick="updateReturnStatus(<?= $return['id'] ?>, 'rejected')"
                                                        title="Rifiuta">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php elseif ($return['status'] === 'approved'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info" 
                                                        onclick="updateReturnStatus(<?= $return['id'] ?>, 'received')"
                                                        title="Segna come ricevuto">
                                                    <i class="fas fa-box"></i>
                                                </button>
                                            <?php elseif ($return['status'] === 'received'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        onclick="updateReturnStatus(<?= $return['id'] ?>, 'refunded')"
                                                        title="Segna come rimborsato">
                                                    <i class="fas fa-euro-sign"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Paginazione resi">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?><?= $status ? '&status=' . $status : '' ?>">Precedente</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= $status ? '&status=' . $status : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?><?= $status ? '&status=' . $status : '' ?>">Successivo</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function updateReturnStatus(returnId, newStatus) {
    const statusLabels = {
        'approved': 'approvare',
        'rejected': 'rifiutare',
        'received': 'segnare come ricevuto',
        'refunded': 'segnare come rimborsato'
    };

    if (confirm(`Sei sicuro di voler ${statusLabels[newStatus]} questo reso?`)) {
        fetch(`/admin/returns/${returnId}/status`, {
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
</script>
