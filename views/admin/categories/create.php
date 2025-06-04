<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../../config/config.php';
}

$pageTitle = 'Aggiungi Categoria';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Aggiungi Nuova Categoria</h3>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/admin/categories/store" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Categoria *</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               required 
                               maxlength="100"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                        <div class="form-text">Il nome della categoria (massimo 100 caratteri)</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  maxlength="500"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                        <div class="form-text">Descrizione opzionale della categoria (massimo 500 caratteri)</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   <?= isset($_POST['is_active']) && $_POST['is_active'] ? 'checked' : 'checked' ?>>
                            <label class="form-check-label" for="is_active">
                                Categoria attiva
                            </label>
                            <div class="form-text">Se deselezionato, la categoria non sarà visibile agli utenti</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/admin/categories" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Torna all'elenco
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salva Categoria
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
