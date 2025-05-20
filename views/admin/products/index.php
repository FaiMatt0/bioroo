<?php
$pageTitle = 'Gestione Prodotti';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item active">Gestione Prodotti</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gestione Prodotti</h1>
    <a href="<?= BASE_URL ?>/admin/products/create" class="btn btn-success">
        <i class="fas fa-plus me-2"></i> Aggiungi prodotto
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Immagine</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Prezzo</th>
                        <th>Stock</th>
                        <th>Stato</th>
                        <th>Data creazione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    require_once MODELS_PATH . '/Product.php';
                    $productModel = new Product();
                    $products = $productModel->getAll();
                    
                    foreach ($products as $product): 
                    ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <img src="<?= BASE_URL ?>/uploads/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="50" class="img-thumbnail">
                            </td>
                            <td><?= $product['name'] ?></td>
                            <td><?= $product['category_name'] ?></td>
                            <td><?= formatPrice($product['price']) ?></td>
                            <td><?= $product['stock_quantity'] ?></td>
                            <td>
                                <?= $product['status'] === 'active' ? '<span class="badge bg-success">Attivo</span>' : '<span class="badge bg-danger">Inattivo</span>' ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($product['created_at'])) ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/products/<?= $product['id'] ?>" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/products/edit/<?= $product['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?= $product['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Modal Elimina Prodotto -->
                                <div class="modal fade" id="deleteProductModal<?= $product['id'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Conferma eliminazione</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Sei sicuro di voler eliminare il prodotto <strong><?= $product['name'] ?></strong>?</p>
                                                <p class="text-danger">Questa azione non può essere annullata.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                                                <form action="<?= BASE_URL ?>/admin/products/delete/<?= $product['id'] ?>" method="POST">
                                                    <button type="submit" class="btn btn-danger">Elimina</button>
                                                </form>
                                            </div>
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
        // Inizializza DataTable per la tabella prodotti
        $('#products-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Italian.json"
            },
            "order": [[0, "desc"]], // Ordina per ID in modo decrescente
            "pageLength": 10
        });
    });
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>