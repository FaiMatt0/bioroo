<?php
$pageTitle = 'Aggiungi Prodotto';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin">Dashboard Admin</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/products">Gestione Prodotti</a></li>
        <li class="breadcrumb-item active">Aggiungi Prodotto</li>
    </ol>
</nav>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Aggiungi nuovo prodotto</h5>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/admin/products/store" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nome prodotto *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Categoria *</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Seleziona categoria</option>
                        <?php
                        require_once MODELS_PATH . '/Category.php';
                        $categoryModel = new Category();
                        $categories = $categoryModel->getAll();
                        
                        foreach ($categories as $category):
                        ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Prezzo *</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="stock_quantity" class="form-label">Quantità in magazzino *</label>
                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione *</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Immagine prodotto</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Formati supportati: JPG, PNG, GIF. Dimensione massima: 2MB.</div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Stato</label>
                <select class="form-select" id="status" name="status">
                    <option value="active" selected>Attivo</option>
                    <option value="inactive">Inattivo</option>
                </select>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="<?= BASE_URL ?>/admin/products" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Torna alla lista
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> Salva prodotto
                </button>
            </div>
        </form>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>