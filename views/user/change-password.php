<?php
$pageTitle = 'Cambia password';
include VIEWS_PATH . '/layouts/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/profile">Profilo</a></li>
        <li class="breadcrumb-item active">Cambia password</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Cambia password</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['password_errors'])): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['password_errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['password_errors']); ?>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/profile/change-password" method="POST">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password attuale</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nuova password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <div class="form-text">La password deve contenere almeno 8 caratteri.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Conferma nuova password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Torna al profilo
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Cambia password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>