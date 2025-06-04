<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Report e Statistiche';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Report e Statistiche</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">Visualizza e esporta report dettagliati sulle vendite, prodotti e utenti.</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistiche generali -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                <h4>Ordini Totali</h4>
                <h2><?= $totalOrders ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-euro-sign fa-2x mb-2"></i>
                <h4>Ricavi Totali</h4>
                <h2><?= formatPrice($totalRevenue) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x mb-2"></i>
                <h4>Prodotti</h4>
                <h2><?= $totalProducts ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4>Utenti</h4>
                <h2><?= $totalUsers ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Opzioni di export -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Esporta Dati</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x text-primary mb-3"></i>
                                <h5>Export Ordini</h5>
                                <p>Scarica un file CSV con tutti gli ordini e i relativi dettagli.</p>
                                <a href="<?= BASE_URL ?>/admin/reports/export?type=orders" class="btn btn-primary">
                                    <i class="fas fa-download me-2"></i>Scarica CSV
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x text-success mb-3"></i>
                                <h5>Export Prodotti</h5>
                                <p>Scarica un file CSV con tutti i prodotti e le relative informazioni.</p>
                                <a href="<?= BASE_URL ?>/admin/reports/export?type=products" class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Scarica CSV
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x text-info mb-3"></i>
                                <h5>Export Utenti</h5>
                                <p>Scarica un file CSV con tutti gli utenti registrati.</p>
                                <a href="<?= BASE_URL ?>/admin/reports/export?type=users" class="btn btn-info">
                                    <i class="fas fa-download me-2"></i>Scarica CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report vendite per mese -->
<?php if (!empty($monthlyStats)): ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Vendite per Mese (Ultimi 12 mesi)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mese</th>
                                <th>Ordini</th>
                                <th>Ricavi</th>
                                <th>Ordine Medio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthlyStats as $month): ?>
                                <tr>
                                    <td><?= $month['month_name'] ?></td>
                                    <td><?= $month['total_orders'] ?></td>
                                    <td><?= formatPrice($month['total_revenue']) ?></td>
                                    <td><?= formatPrice($month['average_order']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Prodotti più venduti -->
<?php if (!empty($topProducts)): ?>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Prodotti Più Venduti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th>Vendite</th>
                                <th>Ricavi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topProducts as $product): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($product['name']) ?></strong><br>
                                        <small class="text-muted"><?= formatPrice($product['price']) ?></small>
                                    </td>
                                    <td><?= $product['total_sold'] ?></td>
                                    <td><?= formatPrice($product['total_revenue']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status ordini -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Status Ordini</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($ordersByStatus)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Quantità</th>
                                    <th>Percentuale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ordersByStatus as $status): ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $statusLabels = [
                                                'pending' => '<span class="badge bg-warning text-dark">In attesa</span>',
                                                'processing' => '<span class="badge bg-info">In elaborazione</span>',
                                                'shipped' => '<span class="badge bg-primary">Spedito</span>',
                                                'delivered' => '<span class="badge bg-success">Consegnato</span>',
                                                'cancelled' => '<span class="badge bg-danger">Annullato</span>'
                                            ];
                                            echo $statusLabels[$status['status']] ?? '<span class="badge bg-secondary">' . htmlspecialchars($status['status']) . '</span>';
                                            ?>
                                        </td>
                                        <td><?= $status['count'] ?></td>
                                        <td><?= number_format($status['percentage'], 1) ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nessun ordine trovato.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Refresh automatico ogni 5 minuti per i dati real-time
setTimeout(function() {
    location.reload();
}, 300000); // 5 minuti

// Download progress indication
document.querySelectorAll('a[href*="export"]').forEach(function(link) {
    link.addEventListener('click', function(e) {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generando...';
        btn.classList.add('disabled');
        
        // Reset after 3 seconds
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('disabled');
        }, 3000);
    });
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
