<?php
if (!defined('VIEWS_PATH')) {
    // Load config when accessed directly
    require_once '../../config/config.php';
}

$pageTitle = 'Login';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Accedi al tuo account</h4>
            </div>            <div class="card-body">
                <?php if (isset($errors['login'])): ?>
                    <div class="alert alert-danger"><?= $errors['login'] ?></div>
                <?php endif; ?>
                
                <?php if (isset($showForgotPassword) && $showForgotPassword): ?>
                    <div class="alert alert-info">
                        <p>Hai dimenticato la password? Inserisci la tua email e ti invieremo istruzioni per reimpostarla.</p>
                        <form action="<?= BASE_URL ?>/auth/login" method="POST">
                            <input type="hidden" name="reset_password" value="1">
                            <div class="mb-3">
                                <input type="email" class="form-control" name="reset_email" placeholder="Inserisci la tua email" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">Invia istruzioni</button>
                            </div>
                        </form>
                        <div class="mt-2 text-center">
                            <a href="<?= BASE_URL ?>/auth/login">Torna al login</a>
                        </div>                    </div>
                <?php else: ?>
                <form action="<?= BASE_URL ?>/auth/login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="invalid-feedback"><?= $errors['password'] ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ricordami</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Accedi</button>
                    </div>                </form>
                <?php endif; ?>
                
                <div class="mt-3 text-center">
                    <p>Non hai un account? <a href="<?= BASE_URL ?>/auth/register">Registrati</a></p>
                    <p><a href="<?= BASE_URL ?>/auth/login?forgot=1">Password dimenticata?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>