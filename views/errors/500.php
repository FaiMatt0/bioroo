<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Errore del server';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-danger">500</h1>
    <h2 class="mb-4">Errore del server</h2>
    <p class="lead mb-5">Si è verificato un errore durante l'elaborazione della tua richiesta. Riprova più tardi.</p>
    <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg">
        <i class="fas fa-home me-2"></i> Torna alla home
    </a>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>