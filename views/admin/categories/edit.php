<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Modifica Categoria';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Modifica Categoria</h3>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/admin/categories/update/<?= $category['id'] ?>" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Categoria *</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               required 
                               maxlength="100"
                               value="<?= htmlspecialchars($category['name']) ?>">
                        <div class="form-text">Il nome della categoria (massimo 100 caratteri)</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  maxlength="500"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                        <div class="form-text">Descrizione opzionale della categoria (massimo 500 caratteri)</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   <?= (!isset($category['is_active']) || $category['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Categoria attiva
                            </label>
                            <div class="form-text">Se deselezionato, la categoria non sarà visibile agli utenti</div>
                        </div>
                    </div>

                    <?php if (isset($category['product_count']) && $category['product_count'] > 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Attenzione:</strong> Questa categoria contiene <?= $category['product_count'] ?> prodotti. 
                            Se la disattivi, i prodotti associati non saranno più visibili nella ricerca per categoria.
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Torna all'elenco
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Aggiorna Categoria
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?= $_SESSION['error_message'] ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['validation_errors'])): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Errori di validazione:</h6>
                <ul class="mb-0">
                    <?php foreach ($_SESSION['validation_errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['validation_errors']); ?>
        <?php endif; ?>

        <!-- Informazioni aggiuntive sulla categoria -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Informazioni Categoria</h5>
            </div>
            <div class="card-body">                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID:</strong> <?= $category['id'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Prodotti associati:</strong> <?= $category['product_count'] ?? 0 ?></p>
                    </div>
                </div>
                
                <?php if (isset($category['product_count']) && $category['product_count'] > 0): ?>
                    <hr>
                    <a href="<?= BASE_URL ?>/admin/products?category=<?= $category['id'] ?>" class="btn btn-outline-info">
                        <i class="fas fa-boxes me-2"></i>Visualizza prodotti in questa categoria
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation for category name
    const nameInput = document.getElementById('name');
    const form = nameInput.closest('form');
    
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        if (name.length < 2) {
            e.preventDefault();
            showAlert('Il nome della categoria deve contenere almeno 2 caratteri.', 'danger');
            nameInput.focus();
            return false;
        }
        
        if (name.length > 100) {
            e.preventDefault();
            showAlert('Il nome della categoria non può superare i 100 caratteri.', 'danger');
            nameInput.focus();
            return false;
        }
    });
    
    // Character count for description
    const descInput = document.getElementById('description');
    if (descInput) {
        descInput.addEventListener('input', function() {
            const remaining = 500 - this.value.length;
            const formText = this.nextElementSibling;
            formText.textContent = `Descrizione opzionale della categoria (${remaining} caratteri rimanenti)`;
            
            if (remaining < 50) {
                formText.className = 'form-text text-warning';
            } else {
                formText.className = 'form-text';
            }
        });
        
        // Trigger initial character count
        descInput.dispatchEvent(new Event('input'));
    }
});

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show mt-3`;
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const form = document.querySelector('form');
    form.parentNode.insertBefore(alertDiv, form.nextSibling);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
