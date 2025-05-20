<?php
$pageTitle = 'Pagamento';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informazioni di pagamento</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/payment/process" method="POST" id="payment-form">
                    <div class="mb-4">
                        <label class="form-label">Metodo di pagamento</label>
                        
                        <div class="mb-3 payment-method">
                            <input type="radio" class="btn-check" name="payment_method" id="credit_card" value="credit_card" checked>
                            <label class="btn btn-outline-primary w-100 text-start" for="credit_card">
                                <i class="fas fa-credit-card me-2"></i> Carta di credito
                            </label>
                            
                            <div class="mt-3 payment-details" id="credit_card_details">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Numero carta</label>
                                    <input type="text" class="form-control" id="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date" class="form-label">Data di scadenza</label>
                                        <input type="text" class="form-control" id="expiry_date" placeholder="MM / AA">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" placeholder="123">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="card_holder" class="form-label">Titolare carta</label>
                                    <input type="text" class="form-control" id="card_holder" placeholder="Nome Cognome">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 payment-method">
                            <input type="radio" class="btn-check" name="payment_method" id="paypal" value="paypal">
                            <label class="btn btn-outline-primary w-100 text-start" for="paypal">
                                <i class="fab fa-paypal me-2"></i> PayPal
                            </label>
                            
                            <div class="mt-3 payment-details d-none" id="paypal_details">
                                <p>Sarai reindirizzato al sito PayPal per completare il pagamento.</p>
                            </div>
                        </div>
                        
                        <div class="mb-3 payment-method">
                            <input type="radio" class="btn-check" name="payment_method" id="bank_transfer" value="bank_transfer">
                            <label class="btn btn-outline-primary w-100 text-start" for="bank_transfer">
                                <i class="fas fa-university me-2"></i> Bonifico bancario
                            </label>
                            
                            <div class="mt-3 payment-details d-none" id="bank_transfer_details">
                                <p>Effettua un bonifico alle seguenti coordinate bancarie:</p>
                                <p>
                                    <strong>Intestatario:</strong> Marketplace Srl<br>
                                    <strong>IBAN:</strong> IT12A0123456789000000123456<br>
                                    <strong>Banca:</strong> Banca Esempio<br>
                                    <strong>Causale:</strong> Ordine #<?= $order['id'] ?>
                                </p>
                                <p class="text-muted">L'ordine sarà processato dopo la ricezione del pagamento.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">Accetto i <a href="<?= BASE_URL ?>/terms">Termini e condizioni</a> e la <a href="<?= BASE_URL ?>/privacy">Privacy Policy</a></label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2">Completa pagamento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Riepilogo ordine</h5>
            </div>
            <div class="card-body">
                <p><strong>Numero ordine:</strong> #<?= $order['id'] ?></p>
                <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                
                <hr>
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotale</span>
                    <strong><?= formatPrice($order['total_amount']) ?></strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Spedizione</span>
                    <strong>Gratuita</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span>Totale</span>
                    <strong class="text-primary fs-4"><?= formatPrice($order['total_amount']) ?></strong>
                </div>
                
                <hr>
                
                <p><strong>Indirizzo di spedizione:</strong></p>
                <address>
                    <?= $order['shipping_address'] ?><br>
                    <?= $order['shipping_city'] ?>, <?= $order['shipping_postal_code'] ?><br>
                    <?= $order['shipping_country'] ?>
                </address>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestisci la visualizzazione dei dettagli del metodo di pagamento
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Nascondi tutti i dettagli
                document.querySelectorAll('.payment-details').forEach(detail => {
                    detail.classList.add('d-none');
                });
                
                // Mostra i dettagli del metodo selezionato
                const detailsId = this.value + '_details';
                document.getElementById(detailsId).classList.remove('d-none');
            });
        });
        
        // Inizializza i campi della carta di credito
        const cardNumber = document.getElementById('card_number');
        if (cardNumber) {
            cardNumber.addEventListener('input', function(e) {
                // Formatta il numero della carta (4 cifre - 4 cifre - 4 cifre - 4 cifre)
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                e.target.value = formattedValue.slice(0, 19); // Max 16 digits + 3 spaces
            });
        }
        
        const expiryDate = document.getElementById('expiry_date');
        if (expiryDate) {
            expiryDate.addEventListener('input', function(e) {
                // Formatta la data di scadenza (MM / AA)
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';
                
                if (value.length > 0) {
                    formattedValue = value.slice(0, 2);
                    if (value.length > 2) {
                        formattedValue += ' / ' + value.slice(2, 4);
                    }
                }
                
                e.target.value = formattedValue;
            });
        }
        
        const cvv = document.getElementById('cvv');
        if (cvv) {
            cvv.addEventListener('input', function(e) {
                // Limita il CVV a 3-4 cifre
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
            });
        }
    });
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>