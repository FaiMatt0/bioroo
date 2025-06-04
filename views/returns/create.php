<?php
if (!defined('VIEWS_PATH')) {
    require_once '../../config/config.php';
}

$pageTitle = 'Richiedi reso per ordine #' . $order['id'];
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/orders">I miei ordini</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>">Ordine #<?= $order['id'] ?></a></li>
        <li class="breadcrumb-item active">Richiedi reso</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Richiedi reso per ordine #<?= $order['id'] ?></h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/returns/store" method="POST" id="returnForm">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    
                    <div class="mb-4">
                        <h6>Seleziona i prodotti da rendere:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50">Seleziona</th>
                                        <th>Prodotto</th>
                                        <th width="100">Prezzo</th>
                                        <th width="100">Qtà ordinata</th>
                                        <th width="120">Qtà da rendere</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input item-checkbox" 
                                                       data-item-id="<?= $item['id'] ?>" 
                                                       data-price="<?= $item['price'] ?>"
                                                       onchange="toggleQuantityInput(this)">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item['image']): ?>
                                                        <img src="<?= BASE_URL ?>/uploads/products/<?= $item['image'] ?>" 
                                                             alt="<?= $item['name'] ?>" 
                                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?= $item['name'] ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= formatPrice($item['price']) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td>
                                                <input type="number" 
                                                       name="items[<?= $item['id'] ?>]" 
                                                       class="form-control quantity-input" 
                                                       min="1" 
                                                       max="<?= $item['quantity'] ?>" 
                                                       value="<?= $item['quantity'] ?>"
                                                       disabled>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reason" class="form-label">Motivo del reso *</label>
                            <select name="reason" id="reason" class="form-select" required>
                                <option value="">Seleziona un motivo</option>
                                <option value="defective">Prodotto difettoso</option>
                                <option value="wrong_item">Articolo sbagliato ricevuto</option>
                                <option value="not_as_described">Non conforme alla descrizione</option>
                                <option value="changed_mind">Ho cambiato idea</option>
                                <option value="damaged_shipping">Danneggiato durante la spedizione</option>
                                <option value="other">Altro</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="refund_method" class="form-label">Metodo di rimborso *</label>
                            <select name="refund_method" id="refund_method" class="form-select" required>
                                <option value="">Seleziona metodo</option>
                                <option value="original_payment">Rimborso sul metodo di pagamento originale</option>
                                <option value="store_credit">Credito negozio</option>
                                <option value="bank_transfer">Bonifico bancario</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="reason_description" class="form-label">Descrizione dettagliata *</label>
                        <textarea name="reason_description" id="reason_description" 
                                  class="form-control" rows="4" 
                                  placeholder="Fornisci una descrizione dettagliata del problema o del motivo del reso..."
                                  required></textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/orders/<?= $order['id'] ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Annulla
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-paper-plane me-2"></i> Invia richiesta reso
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">Riepilogo ordine</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Ordine:</strong> #<?= $order['id'] ?></li>
                    <li><strong>Data:</strong> <?= date('d/m/Y', strtotime($order['created_at'])) ?></li>
                    <li><strong>Totale:</strong> <?= formatPrice($order['total_amount']) ?></li>
                    <li><strong>Stato:</strong> 
                        <span class="badge bg-success">Consegnato</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i> Importante
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="fas fa-check text-success me-2"></i> I resi sono accettati entro 30 giorni dalla consegna</li>
                    <li><i class="fas fa-check text-success me-2"></i> I prodotti devono essere in condizioni originali</li>
                    <li><i class="fas fa-check text-success me-2"></i> Conserva l'imballaggio originale</li>
                    <li><i class="fas fa-check text-success me-2"></i> Riceverai le istruzioni di spedizione via email</li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Totale del reso</h6>
            </div>
            <div class="card-body">
                <div class="h4 text-center" id="returnTotal">€0,00</div>
                <small class="text-muted">Importo che verrà rimborsato</small>
            </div>
        </div>
    </div>
</div>

<script>
function toggleQuantityInput(checkbox) {
    const itemId = checkbox.dataset.itemId;
    const quantityInput = document.querySelector(`input[name="items[${itemId}]"]`);
    
    if (checkbox.checked) {
        quantityInput.disabled = false;
        quantityInput.focus();
    } else {
        quantityInput.disabled = true;
        quantityInput.value = quantityInput.max;
    }
    
    updateReturnTotal();
    updateSubmitButton();
}

function updateReturnTotal() {
    let total = 0;
    
    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const itemId = checkbox.dataset.itemId;
        const price = parseFloat(checkbox.dataset.price);
        const quantityInput = document.querySelector(`input[name="items[${itemId}]"]`);
        const quantity = parseInt(quantityInput.value) || 0;
        
        total += price * quantity;
    });
    
    document.getElementById('returnTotal').textContent = new Intl.NumberFormat('it-IT', {
        style: 'currency',
        currency: 'EUR'
    }).format(total);
}

function updateSubmitButton() {
    const checkedItems = document.querySelectorAll('.item-checkbox:checked').length;
    const submitBtn = document.getElementById('submitBtn');
    
    if (checkedItems > 0) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update totals when quantity changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', updateReturnTotal);
    });
    
    // Form validation
    document.getElementById('returnForm').addEventListener('submit', function(e) {
        const checkedItems = document.querySelectorAll('.item-checkbox:checked').length;
        
        if (checkedItems === 0) {
            e.preventDefault();
            alert('Devi selezionare almeno un prodotto da rendere.');
            return false;
        }
        
        // Validate quantities
        let hasValidQuantity = true;
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const itemId = checkbox.dataset.itemId;
            const quantityInput = document.querySelector(`input[name="items[${itemId}]"]`);
            const quantity = parseInt(quantityInput.value) || 0;
            
            if (quantity <= 0) {
                hasValidQuantity = false;
            }
        });
        
        if (!hasValidQuantity) {
            e.preventDefault();
            alert('Inserisci quantità valide per tutti i prodotti selezionati.');
            return false;
        }
    });
});
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
