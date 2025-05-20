<?php
$pageTitle = 'Login';
include VIEWS_PATH . '/layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Accedi al tuo account</h4>
            </div>
            <div class="card-body">
                <?php if (isset($errors['login'])): ?>
                    <div class="alert alert-danger"><?= $errors['login'] ?></div>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>/login" method="POST">
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
                    </div>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Non hai un account? <a href="<?= BASE_URL ?>/register">Registrati</a></p>
                    <p><a href="<?= BASE_URL ?>/forgot-password">Password dimenticata?</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>