<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Gestione Categorie';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Gestione Categorie</h3>
                <a href="<?= BASE_URL ?>/admin/categories/create" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>Aggiungi Categoria
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error_message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="categoriesTable">
                        <thead class="table-dark">                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrizione</th>
                                <th>Prodotti</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?= $category['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($category['name']) ?></strong>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(substr($category['description'] ?? 'Nessuna descrizione', 0, 100)) ?>
                                            <?= strlen($category['description'] ?? '') > 100 ? '...' : '' ?>
                                        </td>                                        <td>
                                            <span class="badge bg-info"><?= $category['product_count'] ?? 0 ?> prodotti</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= BASE_URL ?>/admin/categories/edit/<?= $category['id'] ?>" 
                                                   class="btn btn-sm btn-warning" title="Modifica">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Elimina"
                                                        onclick="confirmDelete(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-tags fa-3x mb-3"></i>
                                        <p>Nessuna categoria trovata.</p>
                                        <a href="<?= BASE_URL ?>/admin/categories/create" class="btn btn-primary">
                                            Crea la prima categoria
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal di conferma eliminazione -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma Eliminazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare la categoria <strong id="categoryName"></strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Questa azione non può essere annullata.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Elimina</button>
            </div>
        </div>
    </div>
</div>

<script>
let categoryToDelete = null;

function confirmDelete(categoryId, categoryName) {
    categoryToDelete = categoryId;
    document.getElementById('categoryName').textContent = categoryName;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (categoryToDelete) {
        window.location.href = '<?= BASE_URL ?>/admin/categories/delete/' + categoryToDelete;
    }
});

// Initialize DataTable if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#categoriesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
            },
            order: [[0, 'desc']],
            pageLength: 25
        });
    }
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
