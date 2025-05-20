<?php
$pageTitle = 'Contatti';
include VIEWS_PATH . '/layouts/header.php';

// Recupera eventuali errori e dati salvati
$errors = isset($_SESSION['contact_errors']) ? $_SESSION['contact_errors'] : [];
$formData = isset($_SESSION['contact_data']) ? $_SESSION['contact_data'] : [
    'name' => '',
    'email' => '',
    'message' => ''
];

// Rimuovi dati dalla sessione
unset($_SESSION['contact_errors']);
unset($_SESSION['contact_data']);
?>

<div class="container py-5">
    <h1 class="text-center mb-5">Contattaci</h1>
    
    <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="card-title mb-4">Inviaci un messaggio</h2>
                    
                    <form action="<?= BASE_URL ?>/contact/send" method="POST" id="contact-form">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome completo *</label>
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= $formData['name'] ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $formData['email'] ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Oggetto</label>
                            <select class="form-select" id="subject" name="subject">
                                <option value="Informazioni generali">Informazioni generali</option>
                                <option value="Richiesta assistenza">Richiesta assistenza</option>
                                <option value="Reclamo">Reclamo</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Altro">Altro</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Messaggio *</label>
                            <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" id="message" name="message" rows="5" required><?= $formData['message'] ?></textarea>
                            <?php if (isset($errors['message'])): ?>
                                <div class="invalid-feedback"><?= $errors['message'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Invia messaggio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title mb-4">Informazioni di contatto</h2>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary fa-2x me-3"></i>
                        </div>
                        <div>
                            <h5>Indirizzo</h5>
                            <p>Via Roma 123<br>20100 Milano, Italia</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone text-primary fa-2x me-3"></i>
                        </div>
                        <div>
                            <h5>Telefono</h5>
                            <p>+39 02 1234567</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-primary fa-2x me-3"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p>info@marketplace.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-primary fa-2x me-3"></i>
                        </div>
                        <div>
                            <h5>Orari di apertura</h5>
                            <p>Lunedì - Venerdì: 9:00 - 18:00<br>Sabato: 9:00 - 13:00<br>Domenica: Chiuso</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">Dove siamo</h2>
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Includi Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    function initMap() {
        const location = { lat: 45.4642, lng: 9.1900 }; // Milano
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: location,
        });
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "Marketplace HQ"
        });
    }
</script>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>