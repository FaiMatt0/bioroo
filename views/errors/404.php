<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Pagina non trovata';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-primary">404</h1>
    <h2 class="mb-4">Pagina non trovata</h2>
    <p class="lead mb-5">La pagina che stai cercando non esiste o è stata spostata.</p>
    <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg">
        <i class="fas fa-home me-2"></i> Torna alla home
    </a>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>